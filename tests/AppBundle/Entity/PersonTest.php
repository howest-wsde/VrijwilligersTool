<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Person;
use AppBundle\Entity\Organisation;
use Symfony\Component\Validator\Validation;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;

/**
 * Unit test for the Person Entity
 * The test focuses on validation of the different properties and the correct
 * retrieval of said properties.
 * Tested properties: Id(int, 10), FirstName(str, 50), LastName(str, 100),
 * UserName(str, 150), Email (str, 255), Street(str, 255), Number(int, 4),
 * Bus(str, 4), PostalCode(int, 4), City(str, 100), Telephone(str, 20)
 * Untested properties: PassPhrase(str, 60) => this is already tested by Symphony and
 * is a bcrypt hash, PlainPassWord => wasn't present when writing this test (//TODO)
 */
class PersonTest extends \PHPUnit_Framework_TestCase
{
  /**
   * A Symfony validator allowing to check the in-built Symfony validation rules as they apply
   * to the tested entity
   * @var Symfony\Component\Validator\Validator\RecursiveValidator
   */
  public $validator;
  public $basePerson;

  protected function setUp()
  {
    $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    $person = new Person();
    $this->basePerson = $person->setPlainPassword("thisIsSupersecret,Dog!")->setEmail("test@testemail.com");
  }

  /**
   * the dataProvider for testId
   * @return array containing all fringe cases identified @ current
   */
  public function idProvider()
  {
    $person = new Person();
    $person->setPlainPassword("thisIsSupersecret,Dog!");
    $person->setEmail("test@testemail.com");

    return array(
      'normal' => array(1, 0),
      'empty' => array("", 1),
      'object' => array($person, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Id property (Type: Integer, maxlength = 10, must be unique).
   * The test creates a Person, setting its id from an array of
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
      $person = $this->basePerson;
      $person->setId($id);
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($id, $person->getId());
    $this->assertEquals($errorCount, count($errors));
  }

/**
 * A simple test to check whether it's possible to make two Person objects with
 * the same id.  This should be impossible.
 */
  public function testIdUnique(){
    $this->markTestIncomplete("some problem with the unique validator");
    $id = 1;
    $person = clone $this->basePerson;
    $person->setId($id);
    try {
      $person2 = $this->basePerson;
      $person2->setId($id);
      $this->assertNull($person2, 'The second person was instantiated with the same id as the first: please rectify so that this becomes impossible');
    } catch (Exception $e) {
      $this->assertNull($person2, "This should be unreachable if person 2 is not null");
    }
  }

  /**
   * the dataProvider for testFirstName
   * @return array containing all fringe cases identified @ current
   */
  public function firstNameProvider()
  {
    $person = new Person();
    $person->setPlainPassword("thisIsSupersecret,Dog!");
    $person->setEmail("test@testemail.com");

    return array(
      'normal' => array("Florimon", 0),
      'hyphened' => array('Sint-Joost', 0),
      'dot' => array("St.Joris", 0),
      'roof' => array("Înspirationless", 0),
      'eyes' => array("Özer", 0),
      'tail' => array("Anná", 0),
      'reversed tail' => array("Annà", 0),
      '@' => array("joost@the movies straat", 1),
      '/' => array("Sint/Jooststraat", 1),
      ';' => array("i am a programmer; straat;", 1),
      '<' => array("3 is < than 4 street", 1),
      '>' => array("4 is > than 3 street", 1),
      '(' => array("( is an opening brace street", 1),
      ')' => array(") is a closing brace street", 1),
      '[' => array("[ is an opening brace street", 1),
      ']' => array("] is a closing brace street", 1),
      '{' => array("{ is an opening brace street", 1),
      '}' => array("} is a closing brace street", 1),
      ':' => array(": is not allowed", 1),
      '?' => array("? is not allowed", 1),
      '|' => array("| is not allowed", 1),
      '\\' => array("\ is not allowed", 1),
      '!' => array("! is not allowed", 1),
      '#' => array("# is not allowed", 1),
      '$' => array("$ is not allowed", 1),
      '%' => array("% is not allowed", 1),
      '&' => array("& is not allowed", 1),
      '*' => array("* is not allowed", 1),
      '+' => array("+ is not allowed", 1),
      '=' => array("= is not allowed", 1),
      '€' => array("€ is not allowed", 1),
      '_' => array("_ is not allowed", 1),
      '`' => array("` is not allowed", 1),
      '~' => array("~ is not allowed", 1),
      ',' => array(", is not allowed", 1),
      'too short' => array("a", 1),
      'too long' => array("This name is far too long for a person i say governor", 1),
      'empty' => array("", 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the FirstName property (Type: String, maxlength = 50).
   * The test creates a Person, setting its firstName from an array of fringe cases,
   * then checking whether there are validation errors and whether the retreived
   * firstName equals the set firstName.
   * @dataProvider firstNameProvider
   * @param multiple  $firstName   a value from the fringe cases array
   * @param integer   $errorCount  the expected amount of errors
   */
  public function testFirstName($firstName, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    $person = $this->basePerson;
    try {
      $person->setFirstName($firstName);
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($firstName, $person->getFirstName());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataProvider for testLastName
   * @return array containing all fringe cases identified @ current
   */
  public function lastNameProvider()
  {
    $person = new Person();
    $person->setPlainPassword("thisIsSupersecret,Dog!");
    $person->setEmail("test@testemail.com");

    return array(
      'normal' => array("Dragonslayer", 0),
      'hyphened' => array('Sint-Joost', 0),
      'dot' => array("St.Joris", 0),
      'roof' => array("Înspirationless", 0),
      'eyes' => array("Özer", 0),
      'tail' => array("Anná", 0),
      'reversed tail' => array("Annà", 0),
      '@' => array("joost@the movies straat", 1),
      '/' => array("Sint/Jooststraat", 1),
      ';' => array("i am a programmer; straat;", 1),
      '<' => array("3 is < than 4 street", 1),
      '>' => array("4 is > than 3 street", 1),
      '(' => array("( is an opening brace street", 1),
      ')' => array(") is a closing brace street", 1),
      '[' => array("[ is an opening brace street", 1),
      ']' => array("] is a closing brace street", 1),
      '{' => array("{ is an opening brace street", 1),
      '}' => array("} is a closing brace street", 1),
      ':' => array(": is not allowed", 1),
      '?' => array("? is not allowed", 1),
      '|' => array("| is not allowed", 1),
      '\\' => array("\ is not allowed", 1),
      '!' => array("! is not allowed", 1),
      '#' => array("# is not allowed", 1),
      '$' => array("$ is not allowed", 1),
      '%' => array("% is not allowed", 1),
      '&' => array("& is not allowed", 1),
      '*' => array("* is not allowed", 1),
      '+' => array("+ is not allowed", 1),
      '=' => array("= is not allowed", 1),
      '€' => array("€ is not allowed", 1),
      '_' => array("_ is not allowed", 1),
      '`' => array("` is not allowed", 1),
      '~' => array("~ is not allowed", 1),
      ',' => array(", is not allowed", 1),
      'too short' => array("a", 1),
      'too long' => array("This name is far too long for a person i say governor", 1),
      'empty' => array("", 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the LastName property (Type: String, maxlength = 100).
   * The test creates a Person, setting its lastName from an array of fringe cases,
   * then checking whether there are validation errors and whether the
   * retreived lastName equals the set lastName.
   * @dataProvider lastNameProvider
   * @param multiple  $lastName   a value from the fringe cases array
   * @param integer   $errorCount  the expected amount of errors
   */
  public function testLastName($lastName, $errorCount)
  {
    // $this->markTestIncomplete("testing one property at the time");
    try {
      $person = $this->basePerson;
      $person->setLastname($lastName);
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($lastName, $person->getLastname());
    $this->assertEquals($errorCount, count($errors));
  }

  /*  *
   * the dataProvider for testUserName
   * @return array containing all fringe cases identified @ current
   */
  public function userNameProvider()
  {
    return array(
      'normal' => array("Wereldwinkel Roeselare", 0),
      'too short' => array("a", 1),
      'too long' => array("This name is by far too long for any organisation, right!  I mean seriously, what are they thinking?!", 1),
      'empty' => array("", 1),
      'numeric' => array(10, 1),
    );
  }

  /**
   * Test case for the UserName property (Type: String, maxlength = 150).
   * The test creates a Person, setting its userName from an array of fringe cases,
   * then checking whether there are validation errors and whether the retreived
   * userName equals the set userName.
   * @dataProvider userNameProvider
   * @param multiple  $userName   a value from the fringe cases array
   * @param integer   $errorCount  the expected amount of errors
   */
  public function testUserName($userName, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $person = $this->basePerson;
      $person->setUserName($userName);
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($userName, $person->getUserName());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataProvider for testEmail
   * adresses to test against were pulled from is_email's extensive test suite,
   * which can be found @ http://isemail.info/_system/is_email/test/?all%E2%80%8E
   * @return array containing all fringe cases identified @ current
   */
  public function emailProvider()
  {
    return array(
      'normal' => array("test.email@testemail.be", 0),
      'just a word' => array("test", 1),
      'only @' => array("@", 1),
      'no domain' => array("test@", 1),
      'valid (even if no domain extension)' => array("test@io", 0),
      'no local part' => array("@io", 1),
      'no local part #2' => array("@iana.org", 1),
      'valid single local part' => array("test@iana.org", 0),
      'valid, three domain part' => array("test@nominet.org.uk", 0),
      'valid, .museum extension' => array("test@about.museum", 0),
      'valid, single letter local part' => array("a@iana.org", 0),
      'no record warning from dns' => array("test@e.com", 1),
      'no record warning from dns part 2' => array("test@iana.a", 1),
      'dot start' => array(".test@iana.org", 1),
      'dot end' => array("test.@iana.org", 1),
      'consecutive dots' => array("test..iana.org", 1),
      'no domain part 2' => array("test_exa-mple.com", 1),
      'weird shiznit email' => array("!#$%&`*+/=?^`{|}~@iana.org", 0),
      '@ in local part' => array("test\\@test@iana.org", 1),
      'number local part' => array("123@iana.org", 0),
      'number in extension part' => array("test@123.com", 0),
      'number at end' => array("test@iana.123", 1),
      '4 numbers in extension part' => array("test@255.255.255.255", 1),
      'too long' => array("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklmn@iana.org", 1),
      'too long 2' => array("test@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm", 1),
      'no record warning part 3' => array("test@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.com", 1),
      'label too long' => array("test@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm.com", 1),
      'valid hyphenated' => array("test@mason-dixon.com", 0),
      'domain starts with hyphen' => array("test@-iana.org", 1),
      'domein ends with hyphen' => array("test@iana-.com", 1),
      'no record warning part 4' => array("test@iana.co-uk", 1),
      'domain starts with dot' => array("test@.iana.org", 1),
      'extension ends with dot' => array("test@iana.org.", 1),
      'consecutive dots after @' => array("test@iana..com", 1),
      'dotted array after @' => array("a@a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z.a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z.a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z.a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z.a.b.c.d.e.f.g.h.i.j.k.l.m.n.o.p.q.r.s.t.u.v", 1),
      'no record warning part 5' => array("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghi", 1),
      'too long part 3' => array("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghij", 1),
      'too long part 4' => array("a@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefg.hij", 1),
      'domain too long' => array("a@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefg.hijk", 1),
      'quoted string' => array("\"test\"@iana.org", 1),
      'quoted string part 2' => array("\"\"@iana.org", 1),
      'no text in local part' => array("\"\"\"@iana.org", 1),
      'quoted string part 3' => array("\"\\a\"@iana.org", 1),
      'quoted string part 4' => array("\"\\\"\"@iana.org", 1),
      'unclosed quoted string' => array("\"\\\"@iana.org", 1),
      'quoted string part 5' => array("\"\\\\\"@iana.org", 1),
      'no text in local part 2' => array("test\"@iana.org", 1),
      'unclosed quoted string part 2' => array("\"test@iana.org", 1),
      'error after quoted string' => array("\"test\"test@iana.org", 1),
      'expecting a text' => array("test\"text\"@iana.org", 1),
      'expecting a text part 2' => array("\"test\"\"test\"@iana.org", 1),
      'deprecated local part' => array("\"test\".\"test\"@iana.org", 1),
      'quoted string part 6' => array("\"test\\ test\"@iana.org", 1),
      'deprecated local part 2' => array("\"test\".test@iana.org", 1),
      'expecting text part 3' => array("\"test\u0000\"@iana.org", 1),
      'deprecated quoted part' => array("\"test\\\u0000\"@iana.org", 1),
      'something with line endings?' => array("\"test\r\n test\"@iana.org", 1),
      'local part too long' => array("\"abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz abcdefghj\"@iana.org", 1),
      'local part too long part 2`' => array("\"abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz abcdefg\\h\"@iana.org", 1),
      'adress literal' => array("test@’‘ => array(255.255.255.255 1), ", 1),
      'expecting a text part 4' => array("test@a’‘ => array(255.255.255.255 1), ", 1),
      'domain literal' => array("test@’‘ => array(255.255.255 1), ", 1),
      'domain literal part 2' => array("test@’‘ => array(255.255.255.255.255 1), ", 1),
      'domain literal part 3' => array("test@’‘ => array(255.255.255.256 1), ", 1),
      'domain literal part 4' => array("test@’‘ => array(1111:2222:3333:4444:5555:6666:7777:8888 1), ", 1),
      'ipv6 group count' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:5555:6666:7777 1), ", 1),
      'adress literal ipv6' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:5555:6666:7777:8888 1), ", 1),
      'ipv6 group count part 2' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:5555:6666:7777:8888:9999 1), ", 1),
      'ipv6 bad char' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:5555:6666:7777:888G 1), ", 1),
      'deprecated ivp6' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:5555:6666::8888 1), ", 1),
      'adres literal ipv6' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:5555::8888 1), ", 1),
      'ipv6 max groups' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:5555:6666::7777:8888 1), ", 1),
      'ipv6 colon start' => array("test@’‘ => array(IPv6::3333:4444:5555:6666:7777:8888 1), ", 1),
      'ipv6 adress literal' => array("test@’‘ => array(IPv6:::3333:4444:5555:6666:7777:8888 1), ", 1),
      'something with a colon' => array("test@’‘ => array(IPv6:1111::4444:5555::8888 1), ", 1),
      'adres literal' => array("test@’‘ => array(IPv6::: 1), ", 1),
      'ipv6 group count part 2' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:5555:255.255.255.255 1), ", 1),
      'adres literal part 2' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:5555:6666:255.255.255.255 1), ", 1),
      'ipv6 group count part 3' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:5555:6666:7777:255.255.255.255 1), ", 1),
      'adres literal part 3' => array("test@’‘ => array(IPv6:1111:2222:3333:4444::255.255.255.255 1), ", 1),
      'ipv6 max groups part 2' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:5555:6666::255.255.255.255 1), ", 1),
      'something with a colon part 2' => array("test@’‘ => array(IPv6:1111:2222:3333:4444:::255.255.255.255 1), ", 1),
      'colon start' => array("test@’‘ => array(IPv6::255.255.255.255 1), ", 1),
      'deprecated near @' => array(" test @iana.org", 1),
      'deprecated near @ part 2' => array("test@ iana .com", 1),
      'depcreated fws' => array("test . test@iana.org", 1),
      'something with fws' => array("\r\n test@iana.org", 1),
      'deprecated fws part 2' => array("\r\n \r\n test@iana.org", 1),
      'no lf' => array("(\r", 1),
      'comment' => array("(comment)test@iana.org", 1),
      'unclosed comment' => array("((comment)test@iana.org", 1),
      'comment part 2' => array("(comment(comment))test@iana.org", 1),
      'deprecated near @ part 3' => array("test@(comment)iana.org", 1),
      'deprecated near @ part 4' => array("test(comment)@iana.org", 1),
      'text after cfws' => array("test(comment)test@iana.org", 1),
      'deprecated near @ part 5' => array("test@(comment)’‘ => array(255.255.255.255 1), ", 1),
      'comment part 3' => array("(comment)abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm@iana.org", 1),
      'deprecated near @ part 6' => array("test@(comment)abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.com", 1),
      'comment part 4' => array("(comment)test@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghik.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghik.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijk.abcdefghijklmnopqrstuvwxyzabcdefghijk.abcdefghijklmnopqrstu", 1),
      'expecting a text part 3' => array("test@iana.org\n", 1),
      'dns warning no record line 223' => array("test@xn--hxajbheg2az3al.xn--jxalpdlp", 1),
      'valid double hyphen' => array("xn--test@iana.org", 0),
      'domain hyphen end' => array("test@iana.org-", 1),
      'unclosed quoted string line 226' => array("\"test@iana.org", 1),
      'unclosed comment line 227' => array("(test@iana.org", 1),
      'unclosed comment line 228' => array("test@(iana.org", 1),
      'unclosed domain literal line 229' => array("test@’‘ => array(1.2.3.4", 1),
      'unclosed quoted string line 230' => array("\"test\\\"@iana.org", 1),
      'unclosed comment line 231' => array("(comment\\)test@iana.org", 1),
      'unclosed comment line 232' => array("test@iana.org(comment\\)", 1),
      'backslash end' => array("test@iana.org(comment\\", 1),
      'domain literal line 234' => array("test@’‘ => array(RFC-5322-domain-literal 1), ", 1),
      'text after domain literal' => array("test@’‘ => array(RFC-5322 1), -domain-literal 1), ", 1),
      'expecting d text' => array("test@’‘ => array(RFC-5322-’‘ => array(domain-literal 1), ", 1),
      'expecting d text line 237' => array("test@’‘ => array(", 1),
      'domain literal obsd text' => array("test@’‘ => array(\u0007 1), ", 1),
      'domain literal obsd text line 239' => array("test@’‘ => array(RFC-5322-\\\u0007-domain-literal 1), ", 1),
      'domain literal obsd text line 240' => array("test@’‘ => array(RFC-5322-\\\t-domain-literal 1), ", 1),
      'domain literal obsd text line 241' => array("test@’‘ => array(RFC-5322-\\ 1), -domain-literal 1), ", 1),
      'domain literal obsd text line 242' => array("test@’‘ => array(RFC-5322--domain-literal 1), ", 1),
      'unclosed domain literal line 243' => array("test@’‘ => array(RFC-5322-domain-literal\\ 1), ", 1),
      'backslash end line 244' => array("test@’‘ => array(RFC-5322-domain-literal\\", 1),
      'domain literal line 245' => array("test@’‘ => array(RFC 5322 domain literal 1), ", 1),
      'domain literal line 246' => array("test@’‘ => array(RFC-5322-domain-literal 1),  (comment)", 1),
      'expecting a text line 247' => array("@iana.org", 1),
      'expecting a text line 248' => array("test@.org", 1),
      'deprecated q text line 249' => array("\"\"@iana.org", 1),
      'expecting q text line 250' => array("\"\"@iana.org", 1),
      'deprecated QP line 251' => array("\"\\\"@iana.org", 1),
      'deprecated C text line 252' => array("()test@iana.org", 1),
      'expecting C text line 253' => array("()test@iana.org", 1),
      'no lf line 254' => array("test@iana.org\r", 1),
      'no lf line 255' => array("\rtest@iana.org", 1),
      'no lf line 256' => array("\"\rtest\"@iana.org", 1),
      'no lf line 257' => array("(\r)test@iana.org", 1),
      'no lf line 258' => array("test@iana.org(\r)", 1),
      'expecting a text line 259' => array("\ntest@iana.org", 1),
      'expecting Q text line 260' => array("\"\n\"@iana.org", 1),
      'deprecated QP line 261' => array("\"\\\n\"@iana.org", 1),
      'expecting C text line 262' => array("(\n)test@iana.org", 1),
      'expecting a text line 263' => array("\u0007@iana.org", 1),
      'expecting a text line 264' => array("test@\u0007.org", 1),
      'deprecated Q text line 265' => array("\"\u0007\"@iana.org", 1),
      'deprecated QP line 266' => array("\"\\\u0007\"@iana.org", 1),
      'deprecated C text line 267' => array("(\u0007)test@iana.org", 1),
      'CRLF end line 268' => array("\r\ntest@iana.org", 1),
      'CRLF end line 269' => array("\r\n \r\ntest@iana.org", 1),
      'CRLF end line 270' => array(" \r\ntest@iana.org", 1),
      'FWS line 271' => array(" \r\n test@iana.org", 1),
      'CRLF end line 272' => array(" \r\n \r\ntest@iana.org", 1),
      'CRLF x2 line 273' => array(" \r\n\r\ntest@iana.org", 1),
      'CRLF x2 line 274' => array(" \r\n\r\n test@iana.org", 1),
      'FWS line 275' => array("test@iana.org\r\n ", 1),
      'deprecated FWS line 276' => array("test@iana.org\r\n \r\n ", 1),
      'CRLF end line 277' => array("test@iana.org\r\n", 1),
      'no LF line 278' => array("test@iana.org \r", 1),
      'CRLF end line 279' => array("test@iana.org\r\n \r\n", 1),
      'CRLF end line 280' => array("test@iana.org \r\n", 1),
      'FWS line 281' => array("test@iana.org \r\n ", 1),
      'CRLF end line 282' => array("test@iana.org \r\n \r\n", 1),
      'CRLF x2 line 283' => array("test@iana.org \r\n\r\n", 1),
      'CRLF x2 line 284' => array("test@iana.org \r\n\r\n ", 1),
      'deprecated FWS line 285' => array("test@iana. org", 1),
      'no LF line 286' => array("test@’‘ => array(\r", 1),
      'CRLF end line 287' => array("test@’‘ => array(\r\n", 1),
      'FWS line 288' => array(" test@iana.org", 1),
      'FWS line 289' => array("test@iana.org ", 1),
      'colon end line 290' => array("test@’‘ => array(IPv6:1::2: 1), ", 1),
      'expecting q pair line 291' => array("\"test\\©\"@iana.org", 1),
      'rfc5322 domain line 292' => array("test@iana/icann.org", 1),
      'rfc5322 domain line 293' => array("test@iana!icann.org", 1),
      'rfc5322 domain line 294' => array("test@iana?icann.org", 1),
      'rfc5322 domain line 295' => array("test@iana^icann.org", 1),
      'rfc5322 domain line 296' => array("test@iana{icann}.org", 1),
      'deprecated comment line 297' => array("test.(comment)test@iana.org", 1),
      'deprecated comment line 298' => array("test@iana.(comment)org", 1),
      'text after cfws line 299' => array("test@iana(comment)iana.org", 1),
      'FWS line 300' => array("(comment\r\n comment)test@iana.org", 1),
      'rfc5321 tld line 301' => array("test@org", 1),
      'dns warn no mX record line 302' => array("test@example.com", 1),
      'dns warn no record line 303' => array("test@nic.no", 1),
      'empty' => array("", 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Email property (Type: String, maxlength = 255).
   * The test creates a Person, setting its email from an array of fringe cases,
   * then checking whether there are validation errors and whether the retreived
   * email equals the set email.
   * @dataProvider emailProvider
   * @param multiple  $email    a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testEmail($email, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $person = $this->basePerson;
      $person->setEmail($email);
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($email, $person->getEmail());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataProvider for testStreet
   * @return array containing all fringe cases identified @ current
   */
  public function streetProvider()
  {
    return array(
      'normal' => array("Markt", 0),
      'hyphened' => array('Sint-Joost-Ten-Nodestraat', 0),
      'dot' => array("St.Jorisstraat", 0),
      '@' => array("joost@the movies straat", 1),
      '/' => array("Sint/Jooststraat", 1),
      ';' => array("i am a programmer; straat;", 1),
      '<' => array("3 is < than 4 street", 1),
      '>' => array("4 is > than 3 street", 1),
      '(' => array("( is an opening brace street", 1),
      ')' => array(") is a closing brace street", 1),
      '[' => array("[ is an opening brace street", 1),
      ']' => array("] is a closing brace street", 1),
      '{' => array("{ is an opening brace street", 1),
      '}' => array("} is a closing brace street", 1),
      ':' => array(": is not allowed", 1),
      '?' => array("? is not allowed", 1),
      '|' => array("| is not allowed", 1),
      '\\' => array("\ is not allowed", 1),
      '!' => array("! is not allowed", 1),
      '#' => array("# is not allowed", 1),
      '$' => array("$ is not allowed", 1),
      '%' => array("% is not allowed", 1),
      '^' => array("^ is not allowed", 1),
      '&' => array("& is not allowed", 1),
      '*' => array("* is not allowed", 1),
      '+' => array("+ is not allowed", 1),
      '=' => array("= is not allowed", 1),
      '€' => array("€ is not allowed", 1),
      '_' => array("_ is not allowed", 1),
      '`' => array("` is not allowed", 1),
      '~' => array("~ is not allowed", 1),
      ',' => array(", is not allowed", 1),
      'too short' => array("a", 1),
      'too long' => array("This honestly no longer is a streetname.  Streetnames aren't this long.  The longest streetname in Belgium does not even exceed 100 characters, let along 255.  So why did we pick 255 characters as max length?  Your guess is as good as mine.  Or maybe we wanted to be ready for all eventualities that might come in the future... which of course we won't be.", 1),
      'empty' => array("", 1),
      'numeric' => array(10, 1),
    );
  }

  /**
   * Test case for the Street property (Type: String, maxlength = 255, street
   * names cannot contain any special characters other than . or -).
   * The test creates a Person, setting its street from an array of fringe cases,
   * then checking whether there are validation errors and whether the retreived
   * street equals the set street.
   * @dataProvider streetProvider
   * @param multiple  $street    a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testStreet($street, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $person = new Person();
      $person->setStreet($street);
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($street, $person->getStreet());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataProvider for testNumber
   * @return array containing all fringe cases identified @ current
   */
  public function numberProvider()
  {
    return array(
      'normal' => array(27, 0),
      'too low' => array(0, 1),
      'too high' => array(10000, 1),
      'string' => array("", 1),
      'numeric string' => array("123", 1),
      'object' => array(array(), 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Number property (Type: integer, maxlength = 4, min 1).
   * The test creates a Person, setting its number from an array of fringe cases,
   * then checking whether there are validation errors and whether the retreived
   * number equals the set number.
   * @dataProvider numberProvider
   * @param multiple  $number    a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testNumber($number, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $person = $this->basePerson;
      $person->setNumber($number);
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($number, $person->getNumber());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataProvider for testBus
   * @return array containing all fringe cases identified @ current
   */
  public function busProvider()
  {
    return array(
      'normal' => array('27', 0),
      'normal with letters' => array('B8', 0),
      'too long' => array('10000', 1),
      'empty' => array("", 1),
    );
  }

  /**
   * Test case for the Bus property (Type: string, maxlength = 4).
   * The test creates a Person, setting its bus from an array of fringe cases,
   * then checking whether there are validation errors and whether the retreived
   * bus equals the set bus.
   * @dataProvider busProvider
   * @param multiple  $bus    a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testBus($bus, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $person = $this->basePerson;
      $person->setBus($bus);
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($bus, $person->getBus());
    $this->assertEquals($errorCount, count($errors));
  }
  /**
   * the dataProvider for testPostalCode
   * @return array containing all fringe cases identified @ current
   */
  public function postalCodeProvider()
  {
    return array(
      'normal' => array(8690, 0),
      'too low' => array(999, 1),
      'too high' => array(10000, 1),
      'empty string' => array("", 1),
      'string' => array("XY12", 1),
      'string starting numerically' => array("12YX", 1),
      'numeric string' => array("1234", 1),
    );
  }

  /**
   * Test case for the PostalCode property (Type: integer, maxlength = 4, min 1000,
   * max 9999).
   * The test creates a Person, setting its postalCode from an array of fringe cases,
   * then checking whether there are validation errors and whether the retreived
   * postalCode equals the set postalCode.
   * @dataProvider postalCodeProvider
   * @param multiple  $postalCode    a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testPostalCode($postalCode, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $person = $this->basePerson;
      $person->setPostalCode($postalCode);
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($postalCode, $person->getPostalCode());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataProvider for testCity
   * @return array containing all fringe cases identified @ current
   */
  public function cityProvider()
  {
    return array(
      'normal' => array("Roeselare", 0),
      'too short' => array("a", 1),
      'too long' => array("Taiwan boasts the longest city-name in the world, with 163 characters including spaces.  New-Zealand comes in a distant third with 85 characters including spaces.", 1),
      'empty' => array("", 1),
      'numeric' => array(10, 1),
    );
  }

  /**
   * Test case for the City property (Type: string, maxlength = 100, may not contain numbers, may only contain - or . as far as special signs go).
   * The test creates a Person, setting its city from an array of fringe cases, then
   * checking whether there are validation errors and whether the retreived city
   * equals the set city.
   * @dataProvider cityProvider
   * @param multiple  $city    a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testCity($city, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $person = $this->basePerson;
      $person->setCity($city);
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($city, $person->getCity());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataProvider for testTelephone
   * @return array containing all fringe cases identified @ current
   */
  public function telephoneProvider()
  {
    return array(
      'normal' => array("0493123456", 0),
      'too short' => array("0912121", 1),
      'too long' => array("01234567898", 1),
      'mixed starting with numbers' => array("01ABCDEF", 1),
      'mixed' => array("ABC01DEF", 1),
      'empty' => array("", 1),
      'numeric' => array(10, 1),
    );
  }

  /**
   * Test case for the Telephone property (Type: string, maxlength = 10,
   * minlength = 8, content must be numeric).
   * The test creates a Person, setting its telephone number from an array of fringe
   * cases, then checking whether there are validation errors and whether the
   * retreived number equals the set number.
   * @dataProvider telephoneProvider
   * @param multiple  $telephone    a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testTelephone($telephone, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $person = $this->basePerson;
      $person->setTelephone($telephone);
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($telephone, $person->getTelephone());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataProvider for testTelephone
   * @return array containing all fringe cases identified @ current
   */
  public function contactOptionsProvider()  {
    $person = new Person();
    $person->setPlainPassword("thisIsSupersecret,Dog!");
    $personMail = clone $person;
    $personMail->setEmail("test@testemail.be");
    $personTel = clone $person;
    $personTel->setTelephone('0493635780');
    $personOrganisation = clone $person;
    $personOrganisation->setOrganisation(new Organisation());

    return array(
      'only email' => array($personMail, 0),
      'only tel' => array($personTel, 0),
      'only organisation' => array($personOrganisation, 0),
      'email and tel' => array($personMail->setTelephone("0493635780"), 0),
      'email and organisation' => array($personOrganisation->setEmail("test@testemail.be"), 0),
      'tel and organisation' => array($personTel->setOrganisation(new Organisation()), 0),
      'all three' => array($personMail->setOrganisation(new Organisation()), 0),
      'none' => array($person, 1),
    );
}

  /**
   * Test case to check whether at least one contact option has been filled out
   * by the user
   * The test receives a premade Person from the dataprovider and checks whether
   * there are validation errors.
   * @dataProvider contactOptionsProvider
   * @param \Entity\Person  $person       a person with a number of contact options set
   * @param integer         $errorCount   the expected amount of errors
   */
  public function testContactOptions($person, $errorCount)
  {
   $this->markTestIncomplete("testing one property at the time");
    try {
      $errors = $this->validator->validate($person);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($errorCount, count($errors));
  }
}
