<?php declare(strict_types=1);

namespace App\Presentation\Http\Action\Person;

use App\Application\Service\Person\ListPersonPathInterface;
use App\Domain\Person\Query\ListPersonPathQuery;
use App\Presentation\Http\Response\ResponseTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetPersonPath implements RequestHandlerInterface
{
    use ResponseTrait;

    /**
     * @var ListPersonPathInterface
     */
    private $service;

    public function __construct(ListPersonPathInterface $service)
    {
        $this->service = $service;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $query = $request->getQueryParams();
        $query['personID'] = $request->getAttribute('personID');
        $path = $this->service->list(
            ListPersonPathQuery::fromArray($query)
        );

        return $this->ok($path->toArray());
    }
}
