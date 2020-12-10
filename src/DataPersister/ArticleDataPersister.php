<?php


namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Article;

class ArticleDataPersister implements ContextAwareDataPersisterInterface
{
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Article;
    }

    public function persist($data, array $context = [])
    {
        print_r($data);exit;
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
    }
}