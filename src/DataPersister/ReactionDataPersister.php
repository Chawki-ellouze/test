<?php


namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Article;
use App\Entity\Reaction;
use Doctrine\ORM\EntityManagerInterface;

class ReactionDataPersister implements ContextAwareDataPersisterInterface
{
	
	/**
     * @var EntityManagerInterface
     */
    private $_entityManager;
	
	
	    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->_entityManager = $entityManager;
    }
	
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Reaction;
    }

    public function persist($data, array $context = [])
    {
       if(in_array($data->getType(), ['like','dislike'])){
		    $this->_entityManager->persist($data);
			$this->_entityManager->flush();
	   }
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
    }
}