<?php


namespace {
  use Organit\Zoho\Zoho;
  use Organit\Zoho\Item;





  class ItemTest extends TestCase
  {

    protected $org_id;
    protected $item_id;

    public function __construct()
    {
      $this->org_id = '634457325';
      $this->item_id = '402626000000055845';
    }

    /** @test */
    public function it_fetch_item_list()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      $items = $PICAFLOR->items()->index()['data'];
      // dd($items);

      $this->assertTrue(count($items)>0);
    }




    /** @test */
    public function it_fetch_item_object()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');


      // $item_id = $this->item_id;
      $item_id = $PICAFLOR->items()->index()['data'][0]->item_id;

      $org = $PICAFLOR->items()->get($item_id)['data'];
      // dd('$org',$org );


      $this->assertTrue($org->item_id==$item_id);
    }

    /** @test */
    public function it_create_item()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');


      $request = [
                    'currency_code'=>'gg',
                    'name'=>str_random(6),
                    'rate'=>120,
                  ];


      $crea = $PICAFLOR->items()->create($request);
      // dd('$crea',$crea );

      $fetchIt = $PICAFLOR->items()->get($crea['data']->item_id);

      $this->assertTrue($crea['data']->item_id==$fetchIt['data']->item_id);
    }


    /** @test */
    public function it_update_item()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      $item_id = $PICAFLOR->items()->index()['data'][0]->item_id;
      // $zoho = new ItemsAPI;

      // $newName = 'item ' . str_random(9);
      $newName = 'item ' . rand(1000000,9000000);

      $request = [
                    'currency_code'=>'gg',
                    'name'=>$newName,
                    'rate'=>120,
                  ];


      $upd = $PICAFLOR->items()->update($item_id,$request)['data'];


      $fetchIt = $PICAFLOR->items()->get($upd->item_id)['data'];

      $this->assertTrue($upd->name==$fetchIt->name);
    }

    /** @test */
    public function it_delete_item()
    {

      Auth::loginUsingId(1);
      $PICAFLOR = Zoho::organization()->init('634457325');

      //create a new item
      $crea = $PICAFLOR->items()->create([
                                                                    'currency_code'=>'euro',
                                                                    'name'=>'createBeforedelete'.str_random(16),
                                                                    'rate'=>120,
                                                                  ]);



      $response = $PICAFLOR->items()->delete($crea['data']->item_id);
      // dd($response->code );
      $this->assertEquals($response['code'],0);

      // $fetchIt = $PICAFLOR->items()->get($crea->item_id);
      // dd('$fetchIt',$fetchIt );

      //
      // $this->assertTrue($upd->name==$fetchIt->name);
    }





  }

}
