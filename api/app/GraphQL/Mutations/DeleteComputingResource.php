<?php

namespace App\GraphQL\Mutations;

use App\Models\ComputingResource;

class DeleteComputingResource
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
         $item = ComputingResource::find($args['id']);
        $item->delete();

        return $item;
    }
}
