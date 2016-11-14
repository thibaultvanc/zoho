<?php


namespace {
  use Organit\Zoho\Zoho;
  use Organit\Zoho\Person;





  class PersonTest extends TestCase
  {

    protected $org_id;
    protected $person_id;

    public function __construct()
    {
      // $this->org_id = '634457325';
      $this->person_id = '';
    }

    /** @test */
    public function it_fetch_person_list()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');


      // dd('--->',$PICAFLOR->contacts()->personsFromContact('402626000000101003')->index() );



      $persons = $PICAFLOR->contacts()->personsFromContact('402626000000101003')->index()['data'];

      // dd($persons);

      $this->assertTrue(count($persons)>0);
    }




    /** @test */
    public function it_fetch_person_object()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');


      $person_id = $PICAFLOR->contacts()->personsFromContact('402626000000101003')->index()['data'][0]->contact_person_id;
      // $person_id = '402626000000101005';



      $org = $PICAFLOR->contacts()->personsFromContact('402626000000101003')->get($person_id)['data'];
      // dd('$org',$org->contact_id, '$person_id', $person_id );
      // dd('$org',$org );


      $this->assertTrue($org->contact_person_id==$person_id);
    }

    /** @test */
    public function it_create_person()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');


      $request =
      [
        "contact_id"=> "402626000000101003",
        "salutation"=> "Mr.",
        "first_name"=> "Will",
        "last_name"=> "Smith",
        "email"=> "willsmith".rand(1,10000)."@bowmanfurniture.com",
        "phone"=> "+1-925-921-9201",
        "mobile"=> "+1-4054439562"
      ];


      $crea = $PICAFLOR->contacts()->personsFromContact('402626000000101003')->create($request)['data'];


      $fetchIt = $PICAFLOR->contacts()->personsFromContact('402626000000101003')->get($crea->contact_person_id)['data'];

      // dd('$fetchIt',$fetchIt );
      $this->assertTrue($crea->contact_person_id==$fetchIt->contact_person_id);
    }


    /** @test */
    public function it_update_person()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      $person_id = $PICAFLOR->contacts()->personsFromContact('402626000000101003')->index()['data'][0]->contact_person_id;
      // $zoho = new PersonsAPI;

      // $newName = 'person ' . str_random(9);
      $newFirstName = 'person ' . rand(1000000,9000000);
      $newLastName = 'person ' . rand(1000000,9000000);
      $newEmail = rand(1000000,9000000).'@yopmail.com';

      $request =
      [
        "contact_id"=> "402626000000101003",
        "salutation"=> "Mr.",
        "first_name"=> $newFirstName,
        "last_name"=> $newLastName,
        "email"=> $newEmail,
        "phone"=> "+1-925-921-9201",
        "mobile"=> "+1-4054439562"
      ];



      $upd = $PICAFLOR->contacts()->personsFromContact('402626000000101003')->update($person_id,$request)['data'];


      $fetchIt = $PICAFLOR->contacts()->personsFromContact('402626000000101003')->get($upd->contact_person_id)['data'];

      $this->assertTrue($upd->first_name==$fetchIt->first_name);
    }

    /** @test */
    public function it_delete_person()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');


      //create a new person
      $crea = $PICAFLOR->contacts()->personsFromContact('402626000000101003')->create([
                                                                                    "contact_id"=> "402626000000101003",
                                                                                    "salutation"=> "Mr.",
                                                                                    "first_name"=> "Will".str_random(2),
                                                                                    "last_name"=> "Smith".str_random(10),
                                                                                    "email"=> "".rand(1,10000000)."@bowmanfurniture.com",
                                                                                    "phone"=> "+1-925-921-9201",
                                                                                    "mobile"=> "+1-4054439562"
                                                                                  ]);




      $response = $PICAFLOR->contacts()->personsFromContact('402626000000101003')->delete($crea['data']->contact_person_id);
      $this->assertEquals($response['code'],0);

    }





  }

}
