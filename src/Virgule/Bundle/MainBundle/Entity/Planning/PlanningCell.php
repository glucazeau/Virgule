<?php
namespace Virgule\Bundle\MainBundle\Entity\Planning;

use Virgule\Bundle\MainBundle\Entity\Course;

class PlanningCell {
    
    private $course;
    
    private $content = 'vide';
    
    private $rowspan = 0;
    
    public function __construct(Course $course = null) {
        $this->course = $course;
        
        if ($course != null) {
            $this->rowspan = $this->calculateRowspan($course->getStartTime(), $course->getEndTime());
            //echo $course->getStartTime()->format('H:i'). ' - '.$course->getEndTime()->format('H:i');
            $this->content = $course;
        }
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function getRowspan() {
        return $this->rowspan;
    }
    
    private function calculateRowspan($startTime, $endTime) {
        echo $startTime->format('H:i') . '-' . $endTime->format('H:i') . ': ';
        $t1 = strtotime($startTime->format('H:i'));
        $t2 = strtotime($endTime->format('H:i'));
        $minutes = ($t2 - $t1)/60; 
        $rowspan = $minutes / Planning::$cellSize;
        return $rowspan;
    }
}
?>