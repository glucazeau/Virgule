<?php

namespace Virgule\Bundle\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

/**
 * StudentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StudentRepository extends EntityRepository {
    
    public function loadAll() {
        $q = $this
            ->createQueryBuilder('s')
            ->addSelect('s.id, s.firstname, s.lastname, s.gender, s.phoneNumber, s.cellphoneNumber, s.registrationDate, t.id as teacher_id, t.firstName as teacher_firstName, t.lastName as teacher_lastName')
            ->addSelect('c.isoCode, c.label')
            ->innerJoin('s.nativeCountry', 'c')
            ->leftJoin('s.welcomedByTeacher', 't')
            ->getQuery()
        ;
        $students = $q->execute(array(), Query::HYDRATE_ARRAY);
        return $students;
        
    }
}
