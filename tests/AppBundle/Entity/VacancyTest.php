<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Organisation;
// use AppBundle\Entity\Category;
use AppBundle\Entity\Skill;
use Symfony\Component\Validator\Validation;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Unit test for the Vacancy Entity
 * The test focuses on validation of the different properties and the correct
 * retrieval of said properties.
 * Tested properties: title(str, 150), description(str, 2000), StartDate(datetime),
 * EndDate(datetime), CreationTime(datetime), Organisation(T),
 * Category(+, T), Skill(+, T)
 */
class VacancyTest extends \PHPUnit_Framework_TestCase
{
  /**
   * A Symfony validator allowing to check the in-built Symfony validation rules as they apply
   * to the tested entity
   * @var Symfony\Component\Validator\Validator\RecursiveValidator
   */
  public $validator;

  /**
   * The current date & time
   * @var \DateTime
   */
  public $now;

  /**
   * The endDate for the vacancy
   * @var \DateTime
   */
  public $end;

  /**
   * Setting up the validator.
   */
  protected function setUp()
  {
    $this->now = new \DateTime();
    $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
  }

  /**
   * the dataprovider for testTitle
   * @return array containing all fringe cases identified @ current
   */
  public function titleProvider()
  {
    return array(
      'normal' => array("This is a test title.", 0),
      'too short' => array("a", 1),
      'empty' => array("", 1),
      'too long' => array("This is a test title that's too damned long.  Who in their right mind would write a title this long?  What is it that drives them to this utter lunacy?", 1),
      'object' => array(new Vacancy(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Title property (Type: String, maxlength=150).
   * The test populates an array with vacancies, setting their titles, then checking for validation errors and whether or not the getTitle method retrieves the set value correctly.
   * @dataProvider titleProvider
   * @param  multiple $title       a value from the fringe-cases array
   * @param  integer $errorCount   the expected amount of errors
   */
  public function testTitle($title, $errorCount)
  {
    $vacancy = new Vacancy();
    $vacancy->setTitle($title);
    $errors = $this->validator->validate($vacancy);
    $this->assertEquals($title, $vacancy->getTitle());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataprovider for testDescription
   * @return array containing all fringe cases identified @ current
   */
  public function descriptionProvider()
  {
    return array(
      'normal' => array("This is a test description that's neither too long nor too short, thus detailing exactly what this vacancy entails.", 0),
      'too short' => array("too short", 1),
      'empty' => array('', 1),
      'too long' => array("Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea", 1),
      'object' => array(new Vacancy(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Description property (Type: String, maxlength = 2000).
   * The test populates an array with vacancies, setting their description, then checking for validation errors and whether or not the getDescription method retrieves the set value correctly.
   * @dataProvider descriptionProvider
   * @param  String   $description  a value from the fringe-cases array
   * @param  integer  $errorCount   the expected amount of errors for this description
   */
  public function testDescription($description, $errorCount)
  {
    $vacancy = new Vacancy();
    $vacancy->setDescription($description);
    $errors = $this->validator->validate($vacancy);
    $this->assertEquals($description, $vacancy->getDescription());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataprovider for testStartDate
   * @return array containing all fringe cases identified @ current
   */
  public function startDateProvider()
  {
    $past = new \DateTime();
    $past->modify('-1 minute');
    $future = new \DateTime();
    $future->modify('+3 month 1 minute');

    return array(
      'normal' => array(new \DateTime(), 0),
      'in the past' => array($past, 1),
      'more then 3 months in the future' => array($future, 1),
      'empty' => array('', 1),
      'object' => array(new Vacancy(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the StartDate property (Type: DateTime, date cannot be in the past or more than 3 months in the future).
   * The test populates an array with vacancies, setting their startDates, then checking for validation errors and whether or not the getStartDate method retrieves the set value correctly.
   * @dataProvider startDateProvider
   * @param  multiple $startDate    a value from the fringe-cases array
   * @param  integer $errorCount    the expected amount of errors for this title
   */
  public function testStartDate($startDate, $errorCount)
  {
    $vacancy = new Vacancy();
    $vacancy->setStartDate($startDate);
    $errors = $this->validator->validate($vacancy);
    $this->assertEquals($startDate, $vacancy->getStartDate());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataprovider for testEndDate
   * @return array containing all fringe cases identified @ current
   */
  public function endDateProvider()
  {
    $current = new \DateTime();
    $this->now = clone $current;
    $current->modify('+2 month');
    $past = new \DateTime();
    $past->modify('-5 minute');
    $future = new \DateTime();
    $future->modify('+3 year 1 minute');
    $beforeStart = clone $current;
    $beforeStart->modify('-1 minute');

    return array(
      'normal' => array($current, 0),
      'in the past' => array($past, 1),
      'more then one year in the future' => array($future, 1),
      'before the startDate' => array($beforeStart, 1),
      'empty' => array('', 1),
      'object' => array(new Vacancy(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the EndDate property (Type: DateTime, date cannot be in the past or more than 1 year past the startDate in the future, nor can it be closed before the startDate).
   * The test populates an array with vacancies, setting their endDates, then checking for validation errors and whether or not the getEndDate method retrieves the set value correctly.
   * @dataProvider endDateProvider
   * @param  multiple $endDate      a value from the fringe-cases array
   * @param  integer $errorCount    the expected amount of errors for this title
   */
  public function testEndDate($endDate, $errorCount)
  {
    $vacancy = new Vacancy();
    $vacancy->setEndDate($endDate)->setStartDate($this->now);
    $errors = $this->validator->validate($vacancy);
    $this->assertEquals($endDate, $vacancy->getEndDate());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataprovider for testCreationTime
   * @return array containing all fringe cases identified @ current
   */
  public function creationTimeProvider()
  {
    $current = new \DateTime();
    $this->now = clone $current;
    $end = clone $current;
    $end->modify('+2 month');
    $this->end = clone $end;
    $past = new \DateTime();
    $past->modify('-5 minute');
    $future = new \DateTime();
    $future->modify('+1 day 1 minute');
    $beforeStart = clone $current;
    $beforeStart->modify('-1 minute');
    $afterEnd = clone $end;
    $afterEnd->modify('+1 minute');

    return array(
      'normal' => array($current, 0),
      'in the past' => array($past, 1),
      'more then one day in the future' => array($future, 1),
      'before the startDate' => array($beforeStart, 1),
      'after the endDate' => array($afterEnd, 1),
      'empty' => array('', 1),
      'object' => array(new Vacancy(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the CreationTime property (Type: DateTime, It can not be in the past nor more than a day in the future.  It cannot be before/past the start/endDate).
   * The test populates an array with vacancies, setting their creationTime, then checking for validation errors and whether or not the getCreationTime method retrieves the set value correctly.
   * @dataProvider creationTimeProvider
   * @param  DateTime $creationTime  a value from the fringe-cases array
   * @param  integer  $errorCount    the expected amount of errors for this title
   */
  public function testCreationTime($creationTime, $errorCount)
  {
    $vacancy = new Vacancy();
    $vacancy->setCreationTime($creationTime)->setStartDate($this->now)->setEndDate($this->end);
    $errors = $this->validator->validate($vacancy);
    $this->assertEquals($creationTime, $vacancy->getCreationTime());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataprovider for testOrganisation
   * @return array containing all fringe cases identified @ current
   */
  public function organisationProvider()
  {
    $mockBuilder =  $this->getMockBuilder('Organisation');

    return array(
      'normal' => array(new Organisation(), 0),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Organisation property (Type: \AppBundle\Entity\Organisation).
   * The test populates an array with vacancies, setting their organisation, then checking for validation errors and whether or not the getOrganisation method retrieves the set value correctly.
   * @dataProvider organisationProvider
   * @param  multiple $organisation  a value from the fringe-cases array
   * @param  integer $errorCount    the expected amount of errors for this title
   */
  public function testOrganisation($organisation, $errorCount)
  {
    $vacancy = new Vacancy();
    $vacancy->setOrganisation($organisation);
    $errors = $this->validator->validate($vacancy);
    $this->assertEquals($organisation, $vacancy->getOrganisation());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataprovider for testCategory
   * @return array containing all fringe cases identified @ current
   */
  public function categoryProvider()
  {
    // return $this->getArrayCollection(new Category());
  }

  /**
   * Test case for the Category property (Type: \Doctrine\Common\Collections\ArrayCollection of \AppBundle\Entity\Category)
   * The test populates an array with vacancies, setting their category, then checking for validation errors and whether or not the getCategory method retrieves the set value correctly
   * @dataProvider categoryProvider
   * @param  multiple $category     a value from the fringe-cases array
   * @param  integer  $errorCount   the expected amount of errors for this title
   */
  public function testCategory($category, $errorCount)
  {
    $this->markTestIncomplete("Atm Category is still incorrectly named VacancyCategory, rename before running this test and then uncomment the line in the dataprovider.");
    $vacancy = new Vacancy();
    $vacancy->setCategory($category);
    $errors = $this->validator->validate($vacancy);
    $this->assertEquals($category, $vacancy->getCategory());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataprovider for testSkill
   * @return array containing all fringe cases identified @ current
   */
  public function skillProvider()
  {
    return $this->getArrayCollection(new Skill('een skill'));
  }

  /**
   * Test case for the Skill property (Type: \Doctrine\Common\Collections\ArrayCollection of \AppBundle\Entity\Skill).
   * The test populates an array with vacancies, setting their skills, then checking for validation errors and whether or not the getSkill method retrieves the set value correctly.
   * @dataProvider skillProvider
   * @param  multiple $skill       a value from the fringe-cases array
   * @param  integer  $errorCount  the expected amount of errors for this title
   */
  public function testSkill($skill, $errorCount)
  {
    $this->markTestIncomplete("Atm there's still skillproficiency instead of skill in vacancy");
    $vacancy = new Vacancy();
    $vacancy->setSkill($skill);
    $errors = $this->validator->validate($vacancy);
    $this->assertEquals($skill, $vacancy->getSkill());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * Helper function for the skillProvider and categoryProvider functions
   * @param  String $class  the name of the class to be mocked
   * @return array          an array of fringe cases to be tested
   */
  public function getArrayCollection($baseObject){
    $arrayCollection = new ArrayCollection(array(clone $baseObject, clone $baseObject, clone $baseObject));

    return array(
      'normal' => array($arrayCollection, 0),
      'single object' => array($baseObject, 1),
      'empty' => array('', 1),
      'object' => array(new Vacancy(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }
}
