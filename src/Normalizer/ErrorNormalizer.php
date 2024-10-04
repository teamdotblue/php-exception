<?php

declare(strict_types=1);

namespace ADS\Exception\Normalizer;

use ADS\Exception\HttpException\ContextException;
use ADS\Exception\HttpException\PassThroughContextException;
use ADS\Util\StringUtil;
use ApiPlatform\Problem\Serializer\ErrorNormalizerTrait;
use ArrayObject;
use Exception;
use ReflectionClass;
use Sentry\SentrySdk;
use Sentry\State\HubInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

use function sprintf;

#[AutoconfigureTag('serializer.normalizer')]
class ErrorNormalizer implements NormalizerInterface
{
    use ErrorNormalizerTrait;

    private readonly HubInterface $sentryHub;

    public function __construct(
        #[Autowire('@request_stack')]
        private readonly RequestStack $requestStack,
    ) {
        $this->sentryHub = SentrySdk::getCurrentHub();
    }

    final public const FORMAT = 'jsonproblem';

    /**
     * {@inheritDoc}
     *
     * @param Throwable $object
     * @param string    $format
     * @param mixed[]   $context
     *
     * @return array<mixed>|string|int|float|bool|ArrayObject<int|string, mixed>|null
     */
    public function normalize(
        mixed $object,
        string|null $format = null,
        array $context = [],
    ): array|string|int|float|bool|ArrayObject|null {
        $exception = $object;
        if (
            $object->getPrevious() instanceof Throwable
        ) {
            /** @var Throwable $exception */
            $exception = $object->getPrevious();
        }

        /** @var class-string<Throwable> $class */
        $class = $exception::class;
        $status = $context['status_code'] ?? 500;
        if ($object instanceof FlattenException) {
            /** @var class-string<Throwable> $class */
            $class = $object->getClass();
            $status = $object->getStatusCode();
        }

        if ($exception instanceof PassThroughContextException) {
            return [
                'type' => $exception->type(),
                'title' => $exception->title(),
                'detail' => $exception->getMessage(),
                'status' => $exception->getStatusCode(),
                'metadata' => [
                    'request_id' => $this->requestStack->getCurrentRequest()?->headers->get('X-Request-ID'),
                    'sentry_trace' => $this->sentryHub->pushScope()->getPropagationContext()->getTraceContext(),
                ],
                'context' => $exception->context(),
            ];
        }

        $translatable = $exception instanceof ContextException;

        return [
            'type' => self::typeFromException($class),
            'title' => $translatable ? $exception::title() : $exception->getMessage(),
            'detail' => $translatable ? $exception->getMessage() : $this->getErrorMessage($object, $context),
            'status' => $status,
            'metadata' => [
                'request_id' => $this->requestStack->getCurrentRequest()?->headers->get('X-Request-ID'),
                'sentry_trace' => $this->sentryHub->pushScope()->getPropagationContext()->getTraceContext(),
            ],
            'context' => $translatable ? $exception->context()?->toArray() : [],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed[] $context
     */
    public function supportsNormalization(mixed $data, string|null $format = null, array $context = []): bool
    {
        return $format === self::FORMAT && ($data instanceof Exception || $data instanceof FlattenException);
    }

    /** @return array<string, bool> */
    public function getSupportedTypes(string|null $format): array
    {
        return $format === self::FORMAT
            ? [
                Throwable::class => true,
                FlattenException::class => true,
            ]
            : [];
    }

    /** @param class-string<Throwable|ContextException> $exceptionClass */
    public static function typeFromException(string $exceptionClass): string
    {
        $reflectionClass = new ReflectionClass($exceptionClass);

        return sprintf(
            '/problem/%s',
            StringUtil::decamelize($reflectionClass->getShortName(), '-'),
        );
    }
}
