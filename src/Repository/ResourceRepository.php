<?php

namespace RSSReader\Repository;

use RSSReader\Entity\Resource;

class ResourceRepository
{
    /**
     * @param string $name
     * @return Resource|null
     */
    public function getResourceByName(string $name): ?Resource {
        $data =  \ORM::for_table('resources')
            ->where('name', $name)
            ->findOne();

        if (!$data)
            return null;

        return Resource::toObject($data->asArray());
    }

    /**
     * @return Resource[]
     */
    public function getResources(): array {
        $resources =  \ORM::for_table('resources')
            ->findArray();

        return array_map(fn($data) => Resource::toObject($data), $resources);
    }

    public function saveResource(Resource $dataResource): bool {
        $resource = \ORM::for_table('resources')->create();

        $resource->id = $dataResource->getId();
        $resource->name = $dataResource->getName();
        $resource->url = $dataResource->getUrl();
        $resource->created_at = $dataResource->getCreatedAt() !== "" ? $dataResource->getCreatedAt() : date('d-M-Y H:i:s');

        try {
            $resource->save();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getMaxIdResource(): int {
        $resources = $this->getResources();
        $maxID = 0;

        foreach ($resources as $resource){
            if ($resource->getId() > $maxID){
                $maxID = $resource->getId();
            }
        }

        return $maxID + 1;
    }
}