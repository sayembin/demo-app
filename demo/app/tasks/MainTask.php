<?php
require_once __DIR__.'./../../../vendor/autoload.php';
use Phalcon\Cli\Task;

use App\Models\BillboxUser;
use App\Models\BillboxOrder;
use App\Models\BillboxItems;

class MainTask extends Task
{
    public function mainAction()
    {
    }
    /**
     * install the fake data for login 
     * without installing this login not possible
     */
    public function createFakeDataAction()
    {
        try{
           $query1 = "DROP TABLE IF EXISTS billbox_user CASCADE;";
           $this->execQuery($query1);

           $query2 = "CREATE TABLE billbox_user(
            id SERIAL PRIMARY KEY,
            user_name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL,
            password text NOT NULL
         )";
           $this->execQuery($query2);
           
           $query3 = "DROP TABLE IF EXISTS billbox_order CASCADE;";
           $this->execQuery($query3);
           $query4 = "CREATE TABLE billbox_order (
            id SERIAL PRIMARY KEY,
            user_id int NOT NULL,
            create_date bigint ,
            FOREIGN KEY (user_id) REFERENCES billbox_user(id) ON DELETE CASCADE
        )";
           $this->execQuery($query4);
           $query5 = "DROP TABLE IF EXISTS billbox_items";
           $this->execQuery($query5);
           $query6 = "CREATE TABLE billbox_items (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            quantity int NOT NULL,
            item_price float NOT NULL,
            total_price float NOT NULL,
            order_id int NOT NULL,
            FOREIGN KEY (order_id) REFERENCES billbox_order(id) ON DELETE CASCADE
        )";
           $this->execQuery($query6);

           echo "\n Migration script sucessfully run \n";
           $faker = Faker\Factory::create();
            
            $billbox = new BillboxUser();
            $itemNames = ['pizza','pasta','grill','curry'];
            
            $billbox->save(
                [
                    'user_name' =>  $faker->name(),
                    'email'  => 'demo@billbox.com',
                    'password'=>$this->getDI()->getSecurity()->hash('12345')
                ]  
            );
           
                $order = new BillboxOrder();
                $order->save(
                    [
                        'user_id'       =>  $billbox->id,
                        'create_date'   =>  strtotime(date("Y-m-d H:i:s")),
                      
                    ]  
                );
                foreach($itemNames as $iN){
                    $items = new BillboxItems();
                    $itemPrice = $faker->randomFloat($nbMaxDecimals = 2, $min = 10, $max = 20);
                    $quantity = rand(1,2);
                    $items->save(
                        [
                            'name'       => $iN,
                            'quantity'   =>  $quantity,
                            'item_price' => $itemPrice,
                            'total_price'=> ($itemPrice * $quantity),
                            'order_id'   => $order->id
                        ] 
                    );
            }
            
        }catch(Exception $ex){
            throw new Exception($ex->getMessage());

        }
    }
    /**
     * Run the db query 
     * @param string 
     * @return void 
     */
    private  function execQuery(string $sql)
    {
      $this->db->query($sql);
        
    }

    
}