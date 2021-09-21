<?php



use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersInitial::class);
        $this->call(AreasInitial::class);
        $this->call(TiposAreas::class);
        // $this->call(PacientesFake::class);
    }
}
