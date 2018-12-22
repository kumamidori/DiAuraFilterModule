<?php
declare(strict_types=1);
namespace Fob\DiAuraFilterModule\Bridge\AuraFilter;

interface AuraValidateInterface
{
    public function __invoke(\stdClass $subject, $field);
}
