<?php
declare(strict_types=1);

namespace Hosni\HosniSite\EventListener;

use Hosni\HosniSite\Event\CreateConstraintsFromDemandEvent;



final class CreateConstraintsFromDemandEventListener
{
    /**
     * @param CreateConstraintsFromDemandEvent $event
     */
    public function __invoke(CreateConstraintsFromDemandEvent $event): void
    {
        var_dump('tets');die;
        $query = $event->getQuery();
        $demand = $event->getDemand();
        $constraints = $event->getConstraints();

        if ($demand->getIsFocus()) {
            $constraints['isFocus'] = $query->equals('is_focus', $demand->getIsFocus());
        }

        $event->setConstraints($constraints);
    }
}
