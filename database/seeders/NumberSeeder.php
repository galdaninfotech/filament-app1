<?php

namespace Database\Seeders;

use App\Models\Number;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taglines = [
            1 => 'Son of Gun',
            2 => 'You and me',
            3 => 'Buy 2 get 1 free',
            4 => 'Murgi chor / Aar ya paar- number 4',
            5 => 'Hum Panch/ shere punjab',
            6 => 'In a fix/ bottom heavy',
            7 => 'Lucky for all',
            8 => 'Sexy babe at no.8/ one fat major/ one fat lady',
            9 => 'U are mine/ doctor’s time',
            10 => 'Dus numbari / badmash number 10',
            11 => 'Those beautiful legs',
            12 => 'Mid night/  one doz number',
            13 => 'Lucky for some',
            14 => 'Adolosence/ Valentines',
            15 => 'Independence/ Still unkissed',
            16 => 'Sweet 16/ just kissed',
            17 => 'Not so sweet',
            18 => 'Voting age',
            19 => 'Last of the teens',
            20 => 'Blind one score',
            21 => 'The famous restaurant/ marriageable age',
            22 => 'Two little ducks',
            23 => 'You & me',
            24 => 'Two doz number',
            25 => 'Silver Jublee',
            26 => 'Bole mere lips I love 26',
            27 => 'A black raven',
            28 => 'Out on a date',
            29 => 'Rise & shine',
            30 => 'Don’t play dirty/ women goes naught at 30 thirty',
            31 => 'Baskin Robin’s',
            32 => 'Binaca smile',
            33 => 'All the three’s/ dirty knee',
            34 => 'Lions roar',
            35 => 'A flirty wife',
            36 => 'Chatis ka Aankda',
            37 => 'Lime & Lemon',
            38 => 'Me & my fat mate',
            39 => 'Watch your waist line',
            40 => 'Men get naughty at 40',
            41 => 'Kiss and run',
            42 => 'Bharat chodo / Quit Indi',
            43 => 'Climb a tree',
            44 => 'Dil ke chor/ all the fours',
            45 => 'Half way thru',
            46 => 'Choke, chake maro',
            47 => 'Independence',
            48 => 'U are not late',
            49 => 'Marooning Line/ your waistline',
            50 => 'Golden Jubliee/ half a century',
            51 => 'Auspicious',
            52 => 'Pack of the cards',
            53 => 'Pack with joker/ A little wild but free',
            54 => 'House of bamboo door',
            55 => 'All the fives/ dono bhai',
            56 => 'Whisky, Beer do not mix',
            57 => 'Banegi baat at panch aur saat',
            58 => 'Retirement age',
            59 => 'Do I hear a line at 59',
            60 => '3 Score',
            61 => 'Done- dana-dan done',
            62 => 'Over haul is due',
            63 => 'Kisses come free',
            64 => 'Hardcore',
            65 => 'Harem of wives',
            66 => 'All the sixes, click 66',
            67 => 'Haath mein haath 6 aur 7',
            68 => 'Mota seth',
            69 => 'Anyway up/ ulta pulta',
            70 => 'Lucky Blind',
            71 => 'Setting sun',
            72 => 'A different view',
            73 => 'Savitriji',
            74 => 'Still want more',
            75 => 'Diamond jubilee',
            76 => 'Run out of tricks',
            77 => 'Two hockey sticks/ thanda lemon',
            78 => 'Wipe the slate',
            79 => 'Old and senile',
            80 => 'Blind 4 score',
            81 => 'Said and done',
            82 => 'Down with flu',
            83 => 'Grandma’s age',
            84 => 'Haggard and bored',
            85 => 'Still alive',
            86 => 'Pick up a walking stick',
            87 => 'Nearing heaven',
            88 => 'Two fat majors',
            89 => 'Penultimate',
            90 => 'Top of the house blind 90',
        ];

        for($i = 1; $i <= 90; $i++) {
            DB::table('numbers')->insert([
                'number' => $i, 'tagline' => $taglines[$i],
            ]);
        }
    }
}
