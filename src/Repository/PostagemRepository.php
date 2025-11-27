<?php

namespace App\Repository;

use App\Entity\Postagem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postagem>
 */
class PostagemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postagem::class);
    }

    public function findByUsuario($usuario)
    {
        return $this->createQueryBuilder('p')
            ->where('p.usuario = :usuario')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();
    }

    public function findPostagemByLikeTituloLikeConteudo($query)
    {
        return $this->createQueryBuilder('p')
            ->where('p.titulo LIKE :titulo')
            ->orWhere('p.descricao LIKE :descricao')
            ->setParameter('titulo', "%" . $query . "%")
            ->setParameter('descricao', "%" . $query . "%")
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Postagem[] Returns an array of Postagem objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Postagem
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
