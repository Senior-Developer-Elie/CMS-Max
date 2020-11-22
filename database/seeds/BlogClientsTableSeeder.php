<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
class BlogClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clientCSV = "4 Amigos Ranch#4amigosranch.com#Monthly#National#5 Star Cleanup#5starcleanup.com#Quarterly#Rochester NY#Blue Nail Roofing#bluenailroofing.com#Monthly#Dallas TX#Argentina Hunting#bookargentinahunting.com#Monthly#National#Buy Awards & Trophies#buyawardsandtrophies.com#Quarterly#National#Buy Cable Railing#buycablerailing.com#Bi-Monthly#National#CBDepot#cbdepotboutique.com#Bi-Monthly#Rochester NY#CNY Sealing#cnysealing.com#Bi-Monthly#Syracuse NY#Creative Caterers#creativecaterers.com#Bi-Monthly#Rochester NY#Desert Star Limo#desertstarlimo.com#Quarterly#Lancaster CA#DripHouse#driphouse.com#Quarterly#Rochester and Buffalo NY#Everdry NY#everdryny.com#Bi-Monthly#Rochester and Buffalo NY#The Vein Institute#farewellveins.com#Quarterly#Rochester NY#Finnovation Product Development#finnovationpd.com#Bi-Monthly#National#Five Star Improvements#fivestarimprovements.com#Monthly#Rochester NY#Forest Hill Catering#foresthillcatering.com#Bi-Monthly#Rochester NY#Ganguly Law#gangulylaw.com#Bi-Monthly#Rochester NY#Get it Straight#get-it-straight.com#Bi-Monthly#Rochester NY#Guerrilla Tees#guerrillatees.com#Monthly#National#Gulf View Surgery#gulfviewgeneralsurgery.com#Quarterly#Naples FL#Hairzoo#hairzoo.com#Bi-Monthly#Rochester & CA#Hempsol CBD#hempsolcbd.com#Bi-Monthly#National#Mac's Auto Service#macs2.com#Quarterly#Rochester NY#Mantis Medical#mantismed.com#Monthly#All of New York State, Northern PA#Marine Blue#marineblueusa.com#Monthly#Canandaigua NY#Martin's Custom Tidesides#martinscustom.com#Bi-Monthly#Lake Ontario Shore#McKenna's Kitchen & Bath#mckennasrochester.com#Monthly#Rochester NY#Gulf View Aesthetics#naplesmed-spa.com#Quarterly#Naples FL#Napora Heating#naporaheating.com#Bi-Monthly#Rochester NY#Northside Company#northsidecompany.com#Bi-Monthly#Rochester NY#Pinnacle Athletic Campus#pinnacleathleticcampus.com#Bi-Monthly#Rochester NY#Quantum PCR#quantumpcr.com#Monthly#National#Riddle Wellness#riddlewellness.com#Bi-Monthly#Rochester NY#Rochester Industrial Services#rochesterindustrialservices.com#Bi-Monthly#National#Concept II Tile#rochestertile.com#Bi-Monthly#Rochester NY#ROC Hypnosis#rochypnosis.com#Quarterly#Rochester NY#Salvatore's Pizza#salvatores.com#Bi-Monthly#Rochester NY#Sercu Law#serculaw.com#Bi-Monthly#Rochester NY#Shamrock Paving#shamrockpavingroc.com#Bi-Monthly#Rochester NY#Sortino Properties#sortinoproperties.com#Bi-Monthly#Rochester NY#S&S Limousines#sslimousine.com#Monthly#Rochester NY#Stainless Cable Solutions#stainlesscablesolutions.com#Quarterly#National#Syracuse Limo Bus#syracuselimobus.com#6 Months#Syracuse NY#Tasteful Connections#tastefulconnections.com#Quarterly#Rochester NY#The Distillery#thedistillery.com#6 Months#Rochester NY#ThermApparel#thermapparel.com#Quarterly#National#The SteakAger#thesteakager.com#Quarterly#National#Tint Shop#tintshop.com#Bi-Monthly#Rochester NY#Titan Motorworks#titanmotorworks.com#Bi-Monthly#Rochester NY#Tom Todoroff#tomtodoroff.com#Monthly#National#Trovato Associates#trovatoassociates.com#Monthly#Rochester NY#Universal Rocks#universalrocks.com#Bi-Monthly#National#Unlimited Electric#unlimitedelectric.com#Monthly#Rochester NY#VitaLife MN#weightlossminneapolis.com#Monthly#Minneapolis MN#Wonder Windows#wonderwindows.com#Monthly#Rochester & Buffalo NY#Xjus#xjus.com#Monthly#National";
        $data = str_getcsv($clientCSV, '#');

        $clientData = [];
        for( $i = 0; $i < count($data); $i+=4 ) {
            $clientData[] = [
                'name'          => $data[$i],
                'website'       => $data[$i+1],
                'frequency'     => strtolower($data[$i+2]),
                'target_area'   => $data[$i+3],
                'start_date'    => Carbon::now()->startOfMonth()
            ];
        }

        DB::statement("SET foreign_key_checks=0");
        DB::table('blog_clients')->truncate();
        DB::statement("SET foreign_key_checks=1");

        DB::table('blog_clients')->insert($clientData);
    }
}
