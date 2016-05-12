<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Organisation;
use AppBundle\Entity\Skill;
use Symfony\Component\Validator\Validation;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Unit test for the Vacancy Entity
 * The test focuses on validation of the different properties and the correct
 * retrieval of said properties.
 * Tested properties: id(int, 10), title(str, 150), description(str, 2000),
 * StartDate(datetime), EndDate(datetime), Organisation(T), Skill(+, T)
 */
class VacancyTest extends \PHPUnit_Framework_TestCase
{
  /**
   * A Symfony validator allowing to check the in-built Symfony validation rules as they apply
   * to the tested entity
   * @var Symfony\Component\Validator\Validator\RecursiveValidator
   */
  public $validator;
  public $baseVacancy;

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
    $this->baseVacancy = new Vacancy();
    $this->baseVacancy = $this->baseVacancy->setId(1)->setTitle("this is a title")->setDescription("this is a description for a vacancy")->setStartDate($this->now)->setUrlid("/vacancy/this_is_a_title");
  }

  /**
   * the dataProvider for testId
   * @return array containing all fringe cases identified @ current
   */
  public function idProvider()
  {
    return array(
      'normal' => array(1, 0),
      'empty' => array("", 1),
      'object' => array(array(), 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Id property (Type: Integer, maxlength = 10, must be unique).
   * The test creates a Vacancy, setting its id from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived id equals the set id.
   * There is a second test for the Id property: testIdUnique, just below.
   * @dataProvider idProvider
   * @param multiple  $id          a value from the fringe cases array
   * @param integer   $errorCount  the expected amount of errors
   */
  public function testId($id, $errorCount)
  {
   $this->markTestIncomplete("testing one property at the time");
    try {
      $vacancy = $this->baseVacancy;
      $vacancy->setId($id);
      $errors = $this->validator->validate($vacancy);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($id, $vacancy->getId());
    $this->assertEquals($errorCount, count($errors));
  }

/**
 * A simple test to check whether it's possible to make two Vacancy objects with
 * the same id.  This should be impossible.
 */
  public function testIdUnique(){
    $vacancy = clone $this->baseVacancy;
    try {
      $vacancy2 = clone $this->baseVacancy;
      $errors = $this->validator->validate($vacancy2);
    } catch (Exception $e) {
      //this is here clone mainly to sanitize output
    }
    $this->assertGreaterThan(0, $errors, 'vacancy 2 should have at least one validation error as the id is not unique');
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
      'object' => array(array(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Title property (Type: String, maxlength=150).
   * The test creates a Vacancy, setting its title, then checking for validation
   * errors and whether or not the getTitle method retrieves the set value correctly.
   * @dataProvider titleProvider
   * @param  multiple $title       a value from the fringe-cases array
   * @param  integer $errorCount   the expected amount of errors
   */
  public function testTitle($title, $errorCount)
  {
   $this->markTestIncomplete("testing one property at the time");
    try {
      $vacancy = $this->baseVacancy;
      $vacancy->setTitle($title);
      $errors = $this->validator->validate($vacancy);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
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
      'object' => array(array(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Description property (Type: String, maxlength = 2000).
   * The test creates a Vacancy, setting its description, then checking for
   * validation errors and whether or not the getDescription method retrieves
   * the set value correctly.
   * @dataProvider descriptionProvider
   * @param  String   $description  a value from the fringe-cases array
   * @param  integer  $errorCount   the expected amount of errors for this description
   */
  public function testDescription($description, $errorCount)
  {
   $this->markTestIncomplete("testing one property at the time");
    try {
      $vacancy = $this->baseVacancy;
      $vacancy->setDescription($description);
      $errors = $this->validator->validate($vacancy);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
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
      'object' => array(array(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the StartDate property (Type: DateTime, date cannot be in the past or more than 3 months in the future).
   * The test creates a Vacancy, setting its startDates, then checking for
   * validation errors and whether or not the getStartDate method retrieves the
   * set value correctly.
   * @dataProvider startDateProvider
   * @param  multiple $startDate    a value from the fringe-cases array
   * @param  integer $errorCount    the expected amount of errors for this title
   */
  public function testStartDate($startDate, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $vacancy = $this->baseVacancy;
      $vacancy->setStartDate($startDate);
      $errors = $this->validator->validate($vacancy);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
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
      'object' => array(array(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the EndDate property (Type: DateTime, date cannot be in the past or more than 1 year past the startDate in the future, nor can it be closed before the startDate).
   * The test creates a Vacancy, setting its endDates, then checking for
   * validation errors and whether or not the getEndDate method retrieves the
   * set value correctly.
   * @dataProvider endDateProvider
   * @param  multiple $endDate      a value from the fringe-cases array
   * @param  integer $errorCount    the expected amount of errors for this title
   */
  public function testEndDate($endDate, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $vacancy = $this->baseVacancy;
      $vacancy->setEndDate($endDate)->setStartDate($this->now);
      $errors = $this->validator->validate($vacancy);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($endDate, $vacancy->getEndDate());
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
   * The test creates a Vacancy, setting its organisation, then checking for
   * validation errors and whether or not the getOrganisation method retrieves
   * the set value correctly.
   * @dataProvider organisationProvider
   * @param  multiple $organisation  a value from the fringe-cases array
   * @param  integer $errorCount    the expected amount of errors for this title
   */
  public function testOrganisation($organisation, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $vacancy = $this->baseVacancy;
      $vacancy->setOrganisation($organisation);
      $errors = $this->validator->validate($vacancy);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($organisation, $vacancy->getOrganisation());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataprovider for testSkill
   * @return array containing all fringe cases identified @ current
   */
  public function skillProvider()
  {
    return $this->getArrayCollection(new Skill('a skill'));
  }

  /**
   * Test case for the Skill property (Type: \Doctrine\Common\Collections\ArrayCollection of \AppBundle\Entity\Skill).
   * The test creates a Vacancy, setting its skills, then checking for validation
   * errors and whether or not the getSkill method retrieves the set value correctly.
   * @dataProvider skillProvider
   * @param  multiple $skill       a value from the fringe-cases array
   * @param  integer  $errorCount  the expected amount of errors for this title
   */
  public function testSkill($skill, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $vacancy = $this->baseVacancy;
      $vacancy->setSkill($skill);
      $errors = $this->validator->validate($vacancy);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($skill, $vacancy->getSkill());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataprovider for testUrlid
   * @return array containing all fringe cases identified @ current
   */
  public function urlidProvider()
  {
    return array(
      'normal' => array('vacature/eentitel', 'vacature/eentitel', 0),
      'áàûëéîè' => array('vácàtûrë/eéntîtèl', 'vacature/eentitel', 0),
      'ü&_+=!@#$%*()' => array('vacatüre/B&B_Schaepsgerwe(=een plant met 5% aanwezigheid in de benelux! is 500$ of 400€ waard # maar uit)', 'vacature/BnB-Schaepsgerwe', 0),
      ' ' => array('vacature/medewerker winkel', 'vacature/medewerker-winkel', 0),
      '?' => array('vacature/ben jij wie wij zoeken?', 'vacature/ben-jij-wie-wij-zoeken', 0),
      '*=' => array('vacature/medewerker 2*4=9', 'vacature/medewerker-2maal4is9', 0),
      '+' => array('vacature/medewerker 2+1', 'vacature/medewerker-2plus1', 0),
      'ç' => array('vaçature/medewerker', 'vacature/medewerker', 0),
      'too short' => array('v', null, 1),
      'too long' => array('vacature/eenbijzonderlangetitelvanzomaareventjesmeerdan150karaktersdaswelheelerglangnietwaarofdachtjijietsandersdanjijjijomhooggevallenstukjeloslopendw?', null, 1),
      'empty' => array('', null, 1),
      'object' => array(array(), null, 1),
      'numeric' => array(10, null, 1),
      'null' => array(null, null, 1),
    );
  }

  /**
   * Test case for the Urlid property (Type: String, maxlength = 150, minlength = 2).
   * The test creates a Vacancy, setting its Urlid, then checking for validation
   * errors and whether or not the getUrlid method retrieves the set value correctly.
   * Note that the fringe cases array isn't exhaustive, as blimey there are a lot.
   * The most common cases are tested.
   * @dataProvider urlidProvider
   * @param  multiple $urlid       a value from the fringe-cases array
   * @param  expected $expected    the expected value upon using the getter
   * @param  integer  $errorCount  the expected amount of errors for this title
   */
  public function testUrlid($urlid, $expected, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $vacancy = $this->baseVacancy;
      $vacancy->setUrlid($urlid);
      $errors = $this->validator->validate($vacancy);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    if(isset($expected)){
      $this->assertEquals($exptected, $vacancy->getUrlid());
    }
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
      'null' => array(null, 1),
    );
  }
}
