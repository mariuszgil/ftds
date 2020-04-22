<?php

namespace Availability;

interface ResourceRepository
{
    public function save(Resource $resource): void;

    public function get(ResourceId $resourceId): Resource;
}