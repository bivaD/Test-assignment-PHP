<?php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Member>
 *
 * @method Member|null find($id, $lockMode = null, $lockVersion = null)
 * @method Member|null findOneBy(array $criteria, array $orderBy = null)
 * @method Member[]    findAll()
 * @method Member[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }


    // Create operation
    public function createMember(string $name, string $email, \DateTimeInterface $joinDate): ?Member
    {
        $existingMember = $this->findMemberByEmail($email);
        if ($existingMember !== null) {
            return null;
        }
        $member = new Member();
        $member->setName($name)
            ->setEmail($email)
            ->setJoinDate($joinDate)
            ->setDeleted(false);
        $this->getEntityManager()->persist($member);
        $this->getEntityManager()->flush();

        return $member;
    }

    // Read operation
    // Find member by ID
    public function findMemberById(int $id): ?Member
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.id = :id')
            ->andWhere('m.deleted = :deleted')
            ->setParameter('id', $id)
            ->setParameter('deleted', false)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findMemberByEmail(string $email): ?Member
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findAllMembers(): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.deleted = :deleted')
            ->setParameter('deleted', false)
            ->getQuery()
            ->getResult();
    }


    // Update operation
    public function updateMember(Member $member): Member
    {
        $this->getEntityManager()->persist($member);
        $this->getEntityManager()->flush();

        return $member;
    }

    // Delete operation
    public function deleteMember(Member $member): void
    {
        $member->setDeleted(true);
        $this->updateMember($member);
    }
}
