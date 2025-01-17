<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Libs\MasterDataService;

class GeneralMasterData extends Command{
    protected $signature='command:generate_master_data {version}';
    protected $description='Geberate master data - version';

    public function handle(){
        $version=$this->argument("version");
        MasterDataService::GenerateMasterData($version);
    } 
}