<?php

namespace VetCabinet\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserOauth
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class UserOauth
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
