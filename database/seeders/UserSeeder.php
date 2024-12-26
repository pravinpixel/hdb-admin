<?php

namespace Database\Seeders;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #Create User
        $credentials = [
            'member_id'  => 'MMM001',
            'email'      => 'admin@admin.com',
            'password'   => 'admin@123',
            'first_name' => 'admin',
            'last_name'  => 'user',
            'address'    => '',
            'mobile'     => '',
            'created_by' => 'Migration',
            'updated_by' => 'Migration',
        ];
        $userDb = Sentinel::registerAndActivate( $credentials );

         #Create Role
         Sentinel::getRoleRepository()
         ->createModel()
         ->create( [
             'name'       => 'Admin',
             'slug'       => 'admin',
         ] )
         ->users()
         ->attach( $userDb );

         Sentinel::getRoleRepository()
                ->createModel()
                ->create( [
                    'name'       => 'User',
                    'slug'       => 'user',
                    'permissions'=> ['dashboard' => true],
                ] );

            $manager = Sentinel::getRoleRepository()
            ->createModel()
            ->create([
                'name'       => 'Manager',
                'slug'       => 'manager',
                'permissions'=> ["manager.dashboard" => true],
            ]);
            $employee = Sentinel::getRoleRepository()
            ->createModel()
            ->create([
                'name'       => 'Employee',
                'slug'       => 'employee',
                'permissions'=> ["employee.dashboard" => true],
            ]);

            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E1','email'=>'Alice.Liu@cph.sg','password'=>'employee@123','first_name'=>'Alice','last_name'=>'Liu','address'=>'','mobile'=>'0000000001','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E2','email'=>'Alison.Toh@cph.sg','password'=>'employee@123','first_name'=>'Alison','last_name'=>'Toh','address'=>'','mobile'=>'0000000002','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E3','email'=>'Alva.Liow@cph.sg','password'=>'employee@123','first_name'=>'Alva','last_name'=>'Liow','address'=>'','mobile'=>'0000000003','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E4','email'=>'Amily.Ong@cph.sg','password'=>'employee@123','first_name'=>'Amily','last_name'=>'Ong','address'=>'','mobile'=>'0000000004','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E5','email'=>'Andrea.deSouza@cph.sg','password'=>'employee@123','first_name'=>'Andrea','last_name'=>'de Souza','address'=>'','mobile'=>'0000000005','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E6','email'=>'Anthony.Leong@cph.sg','password'=>'employee@123','first_name'=>'Anthony','last_name'=>'Leong','address'=>'','mobile'=>'0000000006','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E7','email'=>'Ariel.Lin@cph.sg','password'=>'employee@123','first_name'=>'Ariel','last_name'=>'Lin','address'=>'','mobile'=>'0000000007','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E8','email'=>'Aston.Tan@cph.sg','password'=>'employee@123','first_name'=>'Aston','last_name'=>'Tan','address'=>'','mobile'=>'0000000008','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E9','email'=>'Averly.Foo@cph.sg','password'=>'employee@123','first_name'=>'Averly','last_name'=>'Foo','address'=>'','mobile'=>'0000000009','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E10','email'=>'Bernadette.Chan@cph.sg','password'=>'employee@123','first_name'=>'Bernadette','last_name'=>'Chan','address'=>'','mobile'=>'0000000010','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E11','email'=>'Bonnibel.Tan@cph.sg','password'=>'employee@123','first_name'=>'Bonnibel','last_name'=>'Tan','address'=>'','mobile'=>'0000000011','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E12','email'=>'Cassandra.See@cph.sg','password'=>'employee@123','first_name'=>'Cassandra','last_name'=>'See','address'=>'','mobile'=>'0000000012','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E13','email'=>'Celeste.Lim@cph.sg','password'=>'employee@123','first_name'=>'Celeste','last_name'=>'Lim','address'=>'','mobile'=>'0000000013','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E14','email'=>'Charlene.Soon@cph.sg','password'=>'employee@123','first_name'=>'Charlene','last_name'=>'Soon','address'=>'','mobile'=>'0000000014','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E15','email'=>'Chay.PeiYun@cph.sg','password'=>'employee@123','first_name'=>'Pei Yun','last_name'=>'Chay','address'=>'','mobile'=>'0000000015','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E16','email'=>'Chelsea.Chew@cph.sg','password'=>'employee@123','first_name'=>'Chelsea','last_name'=>'Chew','address'=>'','mobile'=>'0000000016','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E17','email'=>'Cheryl.Chung@cph.sg','password'=>'employee@123','first_name'=>'Cheryl','last_name'=>'Chung','address'=>'','mobile'=>'0000000017','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E18','email'=>'Chng.JiaYun@cph.sg','password'=>'employee@123','first_name'=>'Jia Yun','last_name'=>'Chng','address'=>'','mobile'=>'0000000018','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E19','email'=>'Christabel.Yip@cph.sg','password'=>'employee@123','first_name'=>'Christabel','last_name'=>'Yip','address'=>'','mobile'=>'0000000019','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E20','email'=>'Christina.Teh@cph.sg','password'=>'employee@123','first_name'=>'Christina','last_name'=>'Teh','address'=>'','mobile'=>'0000000020','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E21','email'=>'Christina.VanHuizen@cph.sg','password'=>'employee@123','first_name'=>'Christina','last_name'=>'Van Huizen','address'=>'','mobile'=>'0000000021','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E22','email'=>'Clarissa.Lui@cph.sg','password'=>'employee@123','first_name'=>'Clarissa','last_name'=>'Lui','address'=>'','mobile'=>'0000000022','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E23','email'=>'Dahshni.Chandran@cph.sg','password'=>'employee@123','first_name'=>'Dahshni','last_name'=>'Chandran','address'=>'','mobile'=>'0000000023','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E24','email'=>'Daphne.Goh@cph.sg','password'=>'employee@123','first_name'=>'Daphne','last_name'=>'Goh','address'=>'','mobile'=>'0000000024','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E25','email'=>'Deborah.Ho@cph.sg','password'=>'employee@123','first_name'=>'Deborah','last_name'=>'Ho','address'=>'','mobile'=>'0000000025','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E26','email'=>'Deborah.Ong@cph.sg','password'=>'employee@123','first_name'=>'Deborah','last_name'=>'Ong','address'=>'','mobile'=>'0000000026','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E27','email'=>'Desdemona.Chong@cph.sg','password'=>'employee@123','first_name'=>'Desdemona','last_name'=>'Chong','address'=>'','mobile'=>'0000000027','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E28','email'=>'Don.Pereira@cph.sg','password'=>'employee@123','first_name'=>'Don','last_name'=>'Pereira','address'=>'','mobile'=>'0000000028','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E29','email'=>'Donald.Yeo@cph.sg','password'=>'employee@123','first_name'=>'Donald','last_name'=>'Yeo','address'=>'','mobile'=>'0000000029','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E30','email'=>'Ernest.Tan@cph.sg','password'=>'employee@123','first_name'=>'Ernest','last_name'=>'Tan','address'=>'','mobile'=>'0000000030','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E31','email'=>'Eve.Tam@cph.sg','password'=>'employee@123','first_name'=>'Eve','last_name'=>'Tam','address'=>'','mobile'=>'0000000031','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E32','email'=>'Faridah.AbuBakar@cph.sg','password'=>'employee@123','first_name'=>'Faridah','last_name'=>'Abu Bakar','address'=>'','mobile'=>'0000000032','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E33','email'=>'Fong.WeiJie@cph.sg','password'=>'employee@123','first_name'=>'Wei Jie','last_name'=>'Fong','address'=>'','mobile'=>'0000000033','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E34','email'=>'Gary.Soh@cph.sg','password'=>'employee@123','first_name'=>'Gary','last_name'=>'Soh','address'=>'','mobile'=>'0000000034','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E35','email'=>'Gloria.Law@cph.sg','password'=>'employee@123','first_name'=>'Gloria','last_name'=>'Law','address'=>'','mobile'=>'0000000035','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E36','email'=>'Ho.ShiYun@cph.sg','password'=>'employee@123','first_name'=>'Shi Yun','last_name'=>'Ho','address'=>'','mobile'=>'0000000036','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E37','email'=>'Imelda.Suryadarma@cph.sg','password'=>'employee@123','first_name'=>'Imelda','last_name'=>'Suryadarma','address'=>'','mobile'=>'0000000037','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E38','email'=>'Iris.Chen@cph.sg','password'=>'employee@123','first_name'=>'Iris','last_name'=>'Chen','address'=>'','mobile'=>'0000000038','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E39','email'=>'Jamie.Gan@cph.sg','password'=>'employee@123','first_name'=>'Jamie','last_name'=>'Gan','address'=>'','mobile'=>'0000000039','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E40','email'=>'Jeannette.Cheong@cph.sg','password'=>'employee@123','first_name'=>'Jeannette','last_name'=>'Cheong','address'=>'','mobile'=>'0000000040','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E41','email'=>'Jeremy.Tang@cph.sg','password'=>'employee@123','first_name'=>'Jeremy','last_name'=>'Tang','address'=>'','mobile'=>'0000000041','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E42','email'=>'Jerlyn.Leong@cph.sg','password'=>'employee@123','first_name'=>'Jerlyn','last_name'=>'Leong','address'=>'','mobile'=>'0000000042','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E43','email'=>'Jesseln.Goh@cph.sg','password'=>'employee@123','first_name'=>'Jesseln','last_name'=>'Goh','address'=>'','mobile'=>'0000000043','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E44','email'=>'Joanna.Chue@cph.sg','password'=>'employee@123','first_name'=>'Joanna','last_name'=>'Chue','address'=>'','mobile'=>'0000000044','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E45','email'=>'Joanne.Seow@cph.sg','password'=>'employee@123','first_name'=>'Joanne','last_name'=>'Seow','address'=>'','mobile'=>'0000000045','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E46','email'=>'Joel.Yap@cph.sg','password'=>'employee@123','first_name'=>'Joel','last_name'=>'Yap','address'=>'','mobile'=>'0000000046','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E47','email'=>'Juliana.Koh@cph.sg','password'=>'employee@123','first_name'=>'Juliana','last_name'=>'Koh','address'=>'','mobile'=>'0000000047','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E48','email'=>'Karen.Sik@cph.sg','password'=>'employee@123','first_name'=>'Karen','last_name'=>'Sik','address'=>'','mobile'=>'0000000048','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E49','email'=>'Koey.XiuMin@cph.sg','password'=>'employee@123','first_name'=>'Xiu Min','last_name'=>'Koey','address'=>'','mobile'=>'0000000049','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E50','email'=>'Lai.WeiWei@cph.sg','password'=>'employee@123','first_name'=>'Wei Wei','last_name'=>'Lai','address'=>'','mobile'=>'0000000050','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E51','email'=>'Law.YaWen@cph.sg','password'=>'employee@123','first_name'=>'Ya Wen','last_name'=>'Law','address'=>'','mobile'=>'0000000051','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E52','email'=>'Lee.Qing@cph.sg','password'=>'employee@123','first_name'=>'Lee','last_name'=>'Qing','address'=>'','mobile'=>'0000000052','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E53','email'=>'Lee.WanLing@cph.sg','password'=>'employee@123','first_name'=>'Wan Ling','last_name'=>'Lee','address'=>'','mobile'=>'0000000053','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E54','email'=>'Lim.GaikSuan@cph.sg','password'=>'employee@123','first_name'=>'Gaik Suan','last_name'=>'Lim','address'=>'','mobile'=>'0000000054','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E55','email'=>'Loh.NeeTian@cph.sg','password'=>'employee@123','first_name'=>'Nee Tian','last_name'=>'Loh','address'=>'','mobile'=>'0000000055','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E56','email'=>'Low.YungLing@cph.sg','password'=>'employee@123','first_name'=>'Yung Ling','last_name'=>'Low','address'=>'','mobile'=>'0000000056','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E57','email'=>'Lynette.Tan@cph.sg','password'=>'employee@123','first_name'=>'Lynette','last_name'=>'Tan','address'=>'','mobile'=>'0000000057','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E58','email'=>'Madhavi.M@cph.sg','password'=>'employee@123','first_name'=>'Madhavi','last_name'=>'Manickavasagam','address'=>'','mobile'=>'0000000058','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E59','email'=>'Mavis.Tang@cph.sg','password'=>'employee@123','first_name'=>'Mavis','last_name'=>'Tang','address'=>'','mobile'=>'0000000059','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E60','email'=>'Melanie.Liang@cph.sg','password'=>'employee@123','first_name'=>'Melanie','last_name'=>'Liang','address'=>'','mobile'=>'0000000060','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E61','email'=>'Melissa.Ng@cph.sg','password'=>'employee@123','first_name'=>'Melissa','last_name'=>'Ng','address'=>'','mobile'=>'0000000061','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E62','email'=>'Michelle.Yap@cph.sg','password'=>'employee@123','first_name'=>'Michelle','last_name'=>'Yap','address'=>'','mobile'=>'0000000062','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E63','email'=>'Ng.JingYi@cph.sg','password'=>'employee@123','first_name'=>'Jing Yi','last_name'=>'Ng','address'=>'','mobile'=>'0000000063','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E64','email'=>'Noradlin.Yusof@cph.sg','password'=>'employee@123','first_name'=>'Noradlin','last_name'=>'Yusof','address'=>'','mobile'=>'0000000064','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E65','email'=>'Nur.Adilah@cph.sg','password'=>'employee@123','first_name'=>'Nur','last_name'=>'Adilah','address'=>'','mobile'=>'0000000065','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E66','email'=>'Pauline.Chia@cph.sg','password'=>'employee@123','first_name'=>'Pauline','last_name'=>'Chia','address'=>'','mobile'=>'0000000066','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E67','email'=>'Peh.YongXian@cph.sg','password'=>'employee@123','first_name'=>'Yong Xian','last_name'=>'Peh','address'=>'','mobile'=>'0000000067','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E68','email'=>'Peter.Tan@cph.sg','password'=>'employee@123','first_name'=>'Peter','last_name'=>'Tan','address'=>'','mobile'=>'0000000068','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E69','email'=>'Ray.Chua@cph.sg','password'=>'employee@123','first_name'=>'Ray','last_name'=>'Chua','address'=>'','mobile'=>'0000000069','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E70','email'=>'Reena.Leong@cph.sg','password'=>'employee@123','first_name'=>'Reena','last_name'=>'Leong','address'=>'','mobile'=>'0000000070','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E71','email'=>'Reena.Senghera@cph.sg','password'=>'employee@123','first_name'=>'Reena','last_name'=>'Senghera','address'=>'','mobile'=>'0000000071','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E72','email'=>'Roger.Liew@cph.sg','password'=>'employee@123','first_name'=>'Roger','last_name'=>'Liew','address'=>'','mobile'=>'0000000072','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E73','email'=>'Saffiah.Zambri@cph.sg','password'=>'employee@123','first_name'=>'Saffiah','last_name'=>'Zambri','address'=>'','mobile'=>'0000000073','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E74','email'=>'Sara.Xu@cph.sg','password'=>'employee@123','first_name'=>'Sara','last_name'=>'Xu','address'=>'','mobile'=>'0000000074','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E75','email'=>'Serene.Lim@cph.sg','password'=>'employee@123','first_name'=>'Serene','last_name'=>'Lim','address'=>'','mobile'=>'0000000075','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E76','email'=>'Shannon.Peh@cph.sg','password'=>'employee@123','first_name'=>'Shannon','last_name'=>'Peh','address'=>'','mobile'=>'0000000076','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E77','email'=>'Shemuel.Yeo@cph.sg','password'=>'employee@123','first_name'=>'Shemuel','last_name'=>'Yeo','address'=>'','mobile'=>'0000000077','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E78','email'=>'Sim.YinLing@cph.sg','password'=>'employee@123','first_name'=>'Yin Ling','last_name'=>'Sim','address'=>'','mobile'=>'0000000078','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E79','email'=>'Sylvia.Ng@cph.sg','password'=>'employee@123','first_name'=>'Sylvia','last_name'=>'Ng','address'=>'','mobile'=>'0000000079','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E80','email'=>'Sylvia.Tan@cph.sg','password'=>'employee@123','first_name'=>'Sylvia','last_name'=>'Tan','address'=>'','mobile'=>'0000000080','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E81','email'=>'Sylvie.Lian@cph.sg','password'=>'employee@123','first_name'=>'Sylvie','last_name'=>'Lian','address'=>'','mobile'=>'0000000081','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E82','email'=>'Tan.Eileen@cph.sg','password'=>'employee@123','first_name'=>'Eileen','last_name'=>'Tan','address'=>'','mobile'=>'0000000082','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E83','email'=>'Tan.ShiJia@cph.sg','password'=>'employee@123','first_name'=>'Shi Jia','last_name'=>'Tan','address'=>'','mobile'=>'0000000083','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E84','email'=>'Tan.SzeYing@cph.sg','password'=>'employee@123','first_name'=>'Sze Ying','last_name'=>'Tan','address'=>'','mobile'=>'0000000084','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E85','email'=>'Tay.YiLing@cph.sg','password'=>'employee@123','first_name'=>'Yi Ling','last_name'=>'Tay','address'=>'','mobile'=>'0000000085','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E86','email'=>'Tessa.Goh@cph.sg','password'=>'employee@123','first_name'=>'Tessa','last_name'=>'Goh','address'=>'','mobile'=>'0000000086','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E87','email'=>'Tracey.Ngai@cph.sg','password'=>'employee@123','first_name'=>'Tracey','last_name'=>'Ngai','address'=>'','mobile'=>'0000000087','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E88','email'=>'Winnie.Wong@cph.sg','password'=>'employee@123','first_name'=>'Winnie','last_name'=>'Wong','address'=>'','mobile'=>'0000000088','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E89','email'=>'Wong.CheeHuey@cph.sg','password'=>'employee@123','first_name'=>'Chee Huey','last_name'=>'Wong','address'=>'','mobile'=>'0000000089','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E90','email'=>'Wong.ShyhShin@cph.sg','password'=>'employee@123','first_name'=>'Shyh Shin','last_name'=>'Wong','address'=>'','mobile'=>'0000000090','created_by' => 'Migration','updated_by' => 'Migration']));
            $employee->users()->attach(Sentinel::registerAndActivate(['member_id'=> 'E91','email'=>'Yusliana.Misran@cph.sg','password'=>'employee@123','first_name'=>'Yusliana','last_name'=>'Misran','address'=>'','mobile'=>'0000000091','created_by' => 'Migration','updated_by' => 'Migration']));
    }
}
