<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\NbpApi\Adapter\CurrentExchangeRateTable\TableA;

use App\Exception\ValidationException;
use App\Service\NbpApi\Adapter\CurrentExchangeRateTable\TableA\CurrentExchangeRateTableAAdapter;
use App\Service\NbpApi\Client\CurrentExchangeRateTable\CurrentExchangeRateTableClientInterface;
use App\Service\NbpApi\DTO\ExchangeRate\ExchangeRateADTO;
use App\Service\NbpApi\DTO\ExchangeRateTable\ExchangeRateTableADTO;
use App\Service\NbpApi\Enum\NbpApiTableTypeEnum;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CurrentExchangeRateTableAAdapterTest extends TestCase
{
    private const string RESPONSE_FILE_PATH = TEST_DATA_DIR . '/Response/NbpApi/CurrentExchangeRateTable/tableAResponse.json';

    private MockObject|CurrentExchangeRateTableClientInterface $client;
    private MockObject|SerializerInterface $serializer;
    private MockObject|ValidatorInterface $validator;

    protected function setUp(): void
    {
        $this->client = $this->createMock(CurrentExchangeRateTableClientInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    public function testConstruct(): void
    {
        $adapter = new CurrentExchangeRateTableAAdapter(
            tableAClient: $this->client,
            serializer: $this->serializer,
            validator: $this->validator,
        );

        $this->assertInstanceOf(CurrentExchangeRateTableAAdapter::class, $adapter);
    }

    public function testSuccessGetTableDTOs(): void
    {
        $adapter = new CurrentExchangeRateTableAAdapter(
            tableAClient: $this->client,
            serializer: $this->serializer,
            validator: $this->validator,
        );

        $responseMock = $this->createMock(ResponseInterface::class);
        $this->client
            ->expects(self::once())
            ->method('getResponse')
            ->willReturn($responseMock);

        $content = file_get_contents(self::RESPONSE_FILE_PATH);
        $responseMock
            ->expects(self::once())
            ->method('getContent')
            ->willReturn($content);

        $DTOs = [
            new ExchangeRateTableADTO(
                rates: [
                    new ExchangeRateADTO(
                        mid: 4.3,
                        currency: 'Euro',
                        code: CurrencyAlpha3::Euro,
                    ),
                ],
                table: NbpApiTableTypeEnum::A,
                no: 'string',
                effectiveDate: new \DateTimeImmutable('2021-01-01'),
            ),
        ];
        $this->serializer
            ->expects(self::once())
            ->method('deserialize')
            ->with(
                $this->equalTo($content),
                $this->equalTo(ExchangeRateTableADTO::class . '[]'),
                $this->equalTo('json'),
            )
            ->willReturn($DTOs);

        $errors = $this->createMock(ConstraintViolationListInterface::class);
        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with(
                $this->equalTo($DTOs),
                $this->equalTo(null),
                $this->equalTo([NbpApiTableTypeEnum::A->value]),
            )
            ->willReturn($errors);
        $errors
            ->expects(self::once())
            ->method('count')
            ->willReturn(0);

        $actual = $adapter->getTableDTOs();

        $this->assertSame($DTOs, $actual);
    }

    public function testFailedGetTableDTOs(): void
    {
        $adapter = new CurrentExchangeRateTableAAdapter(
            tableAClient: $this->client,
            serializer: $this->serializer,
            validator: $this->validator,
        );

        $responseMock = $this->createMock(ResponseInterface::class);
        $this->client
            ->expects(self::once())
            ->method('getResponse')
            ->willReturn($responseMock);

        $content = file_get_contents(self::RESPONSE_FILE_PATH);
        $responseMock
            ->expects(self::once())
            ->method('getContent')
            ->willReturn($content);

        $DTOs = [
            new ExchangeRateTableADTO(
                rates: [
                    new ExchangeRateADTO(
                        mid: 4.3,
                        currency: 'Euro',
                        code: CurrencyAlpha3::Euro,
                    ),
                ],
                table: NbpApiTableTypeEnum::A,
                no: 'string',
                effectiveDate: new \DateTimeImmutable('2021-01-01'),
            ),
        ];
        $this->serializer
            ->expects(self::once())
            ->method('deserialize')
            ->with(
                $this->equalTo($content),
                $this->equalTo(ExchangeRateTableADTO::class . '[]'),
                $this->equalTo('json'),
            )
            ->willReturn($DTOs);

        $errors = $this->createMock(ConstraintViolationListInterface::class);
        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with(
                $this->equalTo($DTOs),
                $this->equalTo(null),
                $this->equalTo([NbpApiTableTypeEnum::A->value]),
            )
            ->willReturn($errors);
        $errors
            ->expects(self::once())
            ->method('count')
            ->willReturn(10);

        $this->expectException(ValidationException::class);
        $adapter->getTableDTOs();
    }
}
