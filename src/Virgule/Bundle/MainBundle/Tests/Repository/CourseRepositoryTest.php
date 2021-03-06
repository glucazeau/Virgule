<?php

namespace Virgule\Bundle\MainBundle\Tests\Repository;

use Virgule\Bundle\MainBundle\Tests\Repository\AbstractRepositoryTest;

class CourseRepositoryTest extends AbstractRepositoryTest {

  // user avec cours
  var $TEACHER_ID = 2;

  private function getRepository() {
    return self::$_em->getRepository('VirguleMainBundle:Course');
  }

  /**
   * @test
   */
  public function getNumberOfCourse_semesterHasThreeCourses_threeCourseReturned() {
    $result = $this->getRepository()->getNumberOfCourse(1);
    $this->assertEquals(4, $result['nb_courses'], "Incorrect number of courses found");
  }

  /**
   * @test
   */
  public function getCoursesByTeacher_teacherhasTwoCoursesForThisSemester_threeCoursesReturned() {
    $semesterId = 1;
    $teacherId = 2;

    $results = $this->getRepository()->getCoursesByTeacher($semesterId, $teacherId);
    $this->assertEquals(2, count($results), "Incorrect number of courses found");
    foreach ($results as $course) {
      $this->assertTrue($course->getSemester()->getId() == 1, 'Returned course is from a different semester');
      $teacherFound = false;
      foreach ($course->getTeachers() as $teacher) {
        if ($teacher->getId() == $teacherId) {
          $teacherFound = true;
        }
      }
      $this->assertTrue($teacherFound);
    }
  }

  /**
   * @test
   */
  public function getNumberOfOverlappingCourses_newCourseEndTimeEqualsStoredCoursedStartTime_resultIsZero() {
    $courseId = 222222;
    $semesterId = 1;
    $dayOfWeek = 1;
    $startTime = new \DateTime('0700');
    $endTime = new \DateTime('0800');
    $classRoomId = 1;

    $result = $this->getRepository()->getNumberOfOverlappingCourses($courseId, $semesterId, $dayOfWeek, $classRoomId, $startTime, $endTime);
    $this->assertEquals(0, $result['nb_courses'], "Incorrect number of courses found");
  }

  /**
   * @test
   */
  public function getNumberOfOverlappingCourses_newCourseStartTimeEqualsStoredCoursedStartTime_resultIsOne() {
    $courseId = 222222;
    $semesterId = 1;
    $dayOfWeek = 1;
    $startTime = new \DateTime('0800');
    $endTime = new \DateTime('0900');
    $classRoomId = 1;

    $result = $this->getRepository()->getNumberOfOverlappingCourses($courseId, $semesterId, $dayOfWeek, $classRoomId, $startTime, $endTime);
    $this->assertEquals(1, $result['nb_courses'], "Incorrect number of courses found");
  }

  /**
   * @test
   */
  public function getNumberOfOverlappingCourses_newCourseEndTimeEqualsStoredCoursedEndTime_resultIsOne() {
    $courseId = 222222;
    $semesterId = 1;
    $dayOfWeek = 1;
    $startTime = new \DateTime('0830');
    $endTime = new \DateTime('0930');
    $classRoomId = 1;

    $result = $this->getRepository()->getNumberOfOverlappingCourses($courseId, $semesterId, $dayOfWeek, $classRoomId, $startTime, $endTime);
    $this->assertEquals(1, $result['nb_courses'], "Incorrect number of courses found");
  }

  /**
   * @test
   */
  public function getNumberOfOverlappingCourses_newCourseStartTimeEqualsStoredCoursedEndTime_resultIsZero() {
    $courseId = 222222;
    $semesterId = 1;
    $dayOfWeek = 1;
    $startTime = new \DateTime('0930');
    $endTime = new \DateTime('1000');
    $classRoomId = 1;

    $result = $this->getRepository()->getNumberOfOverlappingCourses($courseId, $semesterId, $dayOfWeek, $classRoomId, $startTime, $endTime);
    $this->assertEquals(0, $result['nb_courses'], "Incorrect number of courses found");
  }

  /**
   * @test
   */
  public function getNumberOfOverlappingCourses_newCourseHoursContainsStoredCourseHours_resultIsOne() {
    $courseId = 222222;
    $semesterId = 1;
    $dayOfWeek = 1;
    $startTime = new \DateTime('0700');
    $endTime = new \DateTime('1000');
    $classRoomId = 1;

    $result = $this->getRepository()->getNumberOfOverlappingCourses($courseId, $semesterId, $dayOfWeek, $classRoomId, $startTime, $endTime);
    $this->assertEquals(1, $result['nb_courses'], "Incorrect number of courses found");
  }

  /**
   * @test
   */
  public function getNumberOfOverlappingCourses_newCourseHoursAreContainedInStoredCourseHours_resultIsOne() {
    $courseId = 222222;
    $semesterId = 1;
    $dayOfWeek = 1;
    $startTime = new \DateTime('0815');
    $endTime = new \DateTime('0915');
    $classRoomId = 1;

    $result = $this->getRepository()->getNumberOfOverlappingCourses($courseId, $semesterId, $dayOfWeek, $classRoomId, $startTime, $endTime);
    $this->assertEquals(1, $result['nb_courses'], "Incorrect number of courses found");
  }

  /**
   * @test
   */
  public function getNumberOfOverlappingCourses_newCourseHoursAreTheSameThanStoredCourseHours_resultIsOne() {
    $courseId = 222222;
    $semesterId = 1;
    $dayOfWeek = 1;
    $startTime = new \DateTime('0800');
    $endTime = new \DateTime('0930');
    $classRoomId = 1;

    $result = $this->getRepository()->getNumberOfOverlappingCourses($courseId, $semesterId, $dayOfWeek, $classRoomId, $startTime, $endTime);
    $this->assertEquals(1, $result['nb_courses'], "Incorrect number of courses found");
  }

  /**
   * @test
   */
  public function getNumberOfOverlappingCourses_courseHasSamePropertiesButHasDifferentRoom_resultIsZero() {
    $courseId = 222222;
    $semesterId = 1;
    $dayOfWeek = 1;
    $startTime = new \DateTime('0800');
    $endTime = new \DateTime('0930');
    $classRoomId = 2;

    $result = $this->getRepository()->getNumberOfOverlappingCourses($courseId, $semesterId, $dayOfWeek, $classRoomId, $startTime, $endTime);
    $this->assertEquals(0, $result['nb_courses'], "Incorrect number of courses found");
  }

  /**
   * @test
   */
  public function getNumberOfOverlappingCourses_courseHasSamePropertiesButHasDifferentDay_resultIsZero() {
    $courseId = 222222;
    $semesterId = 1;
    $dayOfWeek = 6;
    $startTime = new \DateTime('0800');
    $endTime = new \DateTime('0930');
    $classRoomId = 1;

    $result = $this->getRepository()->getNumberOfOverlappingCourses($courseId, $semesterId, $dayOfWeek, $classRoomId, $startTime, $endTime);
    $this->assertEquals(0, $result['nb_courses'], "Incorrect number of courses found");
  }

  /**
   * @test
   */
  public function getNumberOfOverlappingCourses_courseHasSamePropertiesButHasDifferentSemester_resultIsZero() {
    $courseId = 222222;
    $semesterId = 22222;
    $dayOfWeek = 1;
    $startTime = new \DateTime('0800');
    $endTime = new \DateTime('0930');
    $classRoomId = 1;

    $result = $this->getRepository()->getNumberOfOverlappingCourses($courseId, $semesterId, $dayOfWeek, $classRoomId, $startTime, $endTime);
    $this->assertEquals(0, $result['nb_courses'], "Incorrect number of courses found");
  }

  /**
   * @test
   */
  public function getCoursesIdsByTeacher_teacherHasTwoCoursesForThisSemester_expectedCourseIdsReturned() {
    $semesterId = 1;
    $expectedIds = Array(1, 2);

    $results = $this->getRepository()->getCoursesIdsByTeacher($semesterId, $this->TEACHER_ID);

    $this->assertEquals(2, count($results), "Wrong number of courses returned");

    foreach ($results as $course) {
      $this->assertTrue(in_array($course['id'], $expectedIds), "Course #" . $course['id'] . " not expected");
    }
  }

  /**
   * @test
   */
  public function getCoursesIdsByTeacher_teacherHasNoCourseForThisSemester_noCourseIdReturned() {
    $semesterId = 22222;
    $results = $this->getRepository()->getCoursesIdsByTeacher($semesterId, $this->TEACHER_ID);

    $this->assertEquals(0, count($results), "Wrong number of courses returned");
  }

  /**
   * @test
   */
  public function getCoursesByTeacher_teacherHasTwoCoursesForThisSemester_expectedCoursesReturned() {
    $semesterId = 1;
    $expectedIds = Array(1, 2);

    $results = $this->getRepository()->getCoursesByTeacher($semesterId, $this->TEACHER_ID);
    $this->assertEquals(2, count($results), "Wrong number of courses returned");
    foreach ($results as $course) {
      $this->assertTrue(in_array($course->getId(), $expectedIds), "Course " . $course->getId() . " was not expected");
    }
  }

  /**
   * @test
   */
  public function getCoursesByTeacher_teacherHasNoCourseForThisSemester_noCourseReturned() {
    $semesterId = 22222;

    $results = $this->getRepository()->getCoursesByTeacher($semesterId, $this->TEACHER_ID);
    $this->assertEmpty($results);
  }

  /**
   * @test
   */
  public function loadAll_semesterHasCourse_expectedCoursesLoaded() {
    $semesterId = 1;

    $results = $this->getRepository()->loadAll($semesterId);
    $this->assertEquals(4, count($results));
  }

  /**
   * @test
   */
  public function loadAll_semesterAndClassRoomHaveCourse_expectedCoursesLoaded() {
    $semesterId = 1;

    $results = $this->getRepository()->loadAll($semesterId, Array(1));
    $this->assertEquals(3, count($results));
    foreach ($results as $course) {
      $this->assertEquals('Classroom 11', $course['classroom_name']);
    }
  }

  /**
   * @test
   */
  public function loadAllIdsForSemester_semesterHasCourses_expectedCourseIdsLoaded() {
    $semesterId = 1;
    $expectedIds = Array('1', '2', '3', '4');

    $results = $this->getRepository()->loadAllIdsForSemester($semesterId);
    $this->assertEquals(4, count($results));
    foreach ($results as $course) {
      $this->assertTrue(in_array($course['course_id'], $expectedIds), 'Course ID #' . $course['course_id'] . ' was not expected');
    }
  }

  /**
   * @test
   */
  public function loadAllIdsForSemester_semesterHasNoCourse_noCourseLoaded() {
    $semesterId = 222222;

    $results = $this->getRepository()->loadAllIdsForSemester($semesterId);
    $this->assertEquals(0, count($results), 'Semester should have no courses');
  }

  /**
   * @test
   */
  public function loadAllObjects_semesterHasCourses_expectedCoursesLoaded() {
    $semesterId = 1;
    $expectedIds = Array('1', '2', '3', '4');

    $results = $this->getRepository()->loadAllObjects($semesterId);
    $this->assertEquals(4, count($results));
    foreach ($results as $course) {
      $this->assertNotNull($course);
      $this->assertTrue($course instanceof \Virgule\Bundle\MainBundle\Entity\Course, "Object is of the wrong type");
      $this->assertTrue(in_array($course->getId(), $expectedIds), 'Course ID #' . $course->getId() . ' was not expected');
    }
  }

  /**
   * @test
   */
  public function loadAllObjects_semesterHasNoCourse_noCourseLoaded() {
    $semesterId = 222222;

    $results = $this->getRepository()->loadAllObjects($semesterId);
    $this->assertEquals(0, count($results), 'Semester should have no courses');
  }

  /**
   * @test
   */
  public function findByIds_coursesExist_expectedCoursesLoaded() {
    $courseIds = Array('3', '4');

    $results = $this->getRepository()->findByIds($courseIds);
    $this->assertEquals(2, count($results), 'Two courses should have been found');
    foreach ($results as $course) {
      $this->assertNotNull($course);
      $this->assertTrue($course instanceof \Virgule\Bundle\MainBundle\Entity\Course, "Object is of the wrong type");
      $this->assertTrue(in_array($course->getId(), $courseIds), 'Course ID #' . $course->getId() . ' was not expected');
      $this->assertEquals(1, $course->getSemester()->getId(), 'Course belongs to the wrong semester');
      $this->assertEquals(1, $course->getOrganizationBranch()->getId(), 'Course belongs to the wrong organization branch');
      $this->assertEquals(new \DateTime('1000'), $course->getStartTime());
      $this->assertEquals(new \DateTime('1130'), $course->getEndTime());
    }
  }

  /**
   * @test
   */
  public function findByIds_coursesDoesntExist_noCourseLoaded() {
    $courseIds = Array('222222', '333333');

    $results = $this->getRepository()->findByIds($courseIds);
    $this->assertEquals(0, count($results), 'No course should have been found');
  }

  /**
   * @test
   */
  public function getCoursesByStudent_studentHasCoursesOnDifferentSemesters_allCoursesAreLoaded() {
    $studentId = 1;

    $results = $this->getRepository()->getCoursesByStudent($studentId);
    $this->assertEquals(3, count($results), 'No course have been found');
  }

  /**
   * @test
   */
  public function getNumberOfEnrolledStudents_oneCourseGivenWithTwoStudents_twoReturned() {
    $courseIds = Array(1);

    $results = $this->getRepository()->getNumberOfEnrolledStudents($courseIds);
    $this->assertEquals(1, count($results), 'Wrong number of courses returned');
    $this->assertEquals(2, $results[1]['nb_students'], 'Wrong number of students found');
  }

  /**
   * @test
   */
  public function getNumberOfEnrolledStudents_twoCourseGivenWithThreeStudentsTotal_threeReturned() {
    $courseIds = Array(1, 2);

    $results = $this->getRepository()->getNumberOfEnrolledStudents($courseIds);
    $this->assertEquals(2, count($results), 'Wrong number of courses returned');
    $this->assertEquals(2, $results[1]['nb_students'], 'Wrong number of students found for course #1');
    $this->assertEquals(1, $results[2]['nb_students'], 'Wrong number of students found for course #2');
  }

  /**
   * @test
   */
  public function getNumberOfEnrolledStudents_oneCourseGivenWithNoStudents_zeroReturned() {
    $courseIds = Array(3);

    $results = $this->getRepository()->getNumberOfEnrolledStudents($courseIds);
    $this->assertEquals(0, count($results), 'Wrong number of courses returned');
  }

}
