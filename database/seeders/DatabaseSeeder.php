<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\LabelExtractor;
use DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('admin_roles')->truncate();

        DB::table('admin_roles')->insert([
            'id' => 1,
            'name' => 'Administrator',
            'slug' => 'administrator',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('label_extractors')->truncate();
        $label1 = new LabelExtractor();
        $label1->enabled = true;
        $label1->priority = 1;
        $label1->property = "skos:prefLabel";
        $label1->save();

        $label2 = new LabelExtractor();
        $label2->enabled = true;
        $label2->priority =2;
        $label2->property = "rdfs:label";
        $label2->save();

        DB::table('link_types')->truncate();
        DB::table('link_types')->insert(
        [
            //SKOS
        [
            'user_id' => 0,
            'group' => "SKOS",
            'inner' => "Exact Match",
            'value' => "http://www.w3.org/2004/02/skos/core#exactMatch",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'user_id' => 0,
            'group' => "SKOS",
            'inner' => "Narrow Match",
            'value' => "http://www.w3.org/2004/02/skos/core#narrowMatch",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'user_id' => 0,
            'group' => "SKOS",
            'inner' => "Broad Match",
            'value' => "http://www.w3.org/2004/02/skos/core#broadMatch",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'user_id' => 0,
            'group' => "SKOS",
            'inner' => "Related Match",
            'value' => "http://www.w3.org/2004/02/skos/core#relatedMatch",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'user_id' => 0,
            'group' => "SKOS",
            'inner' => "Close Match",
            'value' => "http://www.w3.org/2004/02/skos/core#closeMatch",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
            
            //OWL
        [
            'user_id' => 0,
            'group' => "OWL",
            'inner' => "Same As",
            'value' => "http://www.w3.org/2002/07/owl#sameAs",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'user_id' => 0,
            'group' => "OWL",
            'inner' => "Disjoint With",
            'value' => "http://www.w3.org/2002/07/owl#disjointWith",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'user_id' => 0,
            'group' => "OWL",
            'inner' => "Equivalent Class",
            'value' => "http://www.w3.org/2002/07/owl#equivalentClass",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'user_id' => 0,
            'group' => "OWL",
            'inner' => "Complement Of",
            'value' => "http://www.w3.org/2002/07/owl#complementOf",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ], 
        [
            'user_id' => 0,
            'group' => "OWL",
            'inner' => "Different From",
            'value' => "http://www.w3.org/2002/07/owl#differentFrom",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'user_id' => 0,
            'group' => "OWL",
            'inner' => "Equivalent Property",
            'value' => "http://www.w3.org/2002/07/owl#equivalentProperty",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'user_id' => 0,
            'group' => "OWL",
            'inner' => "Inverse Of",
            'value' => "http://www.w3.org/2002/07/owl#inverseOf",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        
            //RDFS
        [
            'user_id' => 0,
            'group' => "RDFS",
            'inner' => "See Also",
            'value' => "http://www.w3.org/2000/01/rdf-schema#seeAlso",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'user_id' => 0,
            'group' => "RDFS",
            'inner' => "Sub-class Of",
            'value' => "http://www.w3.org/2000/01/rdf-schema#subClassOf",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'user_id' => 0,
            'group' => "RDFS",
            'inner' => "Sub Property Of",
            'value' => "http://www.w3.org/2000/01/rdf-schema#subPropertyOf",
            'public' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
            
    ]);

    DB::table('settings')->insert([
        'id' => 1,
        'name' => 'Default',
        'user_id' => 1,
        'valid' => true,
        'public' => true,
        'resource_file_name' => 'project2_config.xml',
        'suggestion_provider_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('suggestion_providers')->insert([
        'id' => 1,
        'name' => 'Silk',
        'description' => 'Silk Config',
        'configuration' => '\\App\\Models\\SuggestionConfigurations\\SilkConfiguration',
        'job' => '\\App\\Jobs\\RunSilk',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('admin_menu')->insert([
        [
            'id' => 1,
            'parent_id' => 0,
            'order' => 1,
            'title' => 'Dashboard',
            'icon' => 'icon-chart-bar',
            'uri' => '/',
            'created_at' => Carbon::parse('2024-06-12 10:02:09'),
            'updated_at' => Carbon::parse('2024-06-12 10:02:09')
        ],
        [
            'id' => 2,
            'parent_id' => 0,
            'order' => 2,
            'title' => 'My Ontologies',
            'icon' => 'icon-adjust',
            'uri' => '/mygraphs',
            'created_at' => Carbon::parse('2024-06-12 10:02:09'),
            'updated_at' => Carbon::parse('2024-06-12 10:02:09')
        ],
        [
            'id' => 3,
            'parent_id' => 0,
            'order' => 3,
            'title' => 'My Projects',
            'icon' => 'icon-atom',
            'uri' => '/myprojects',
            'created_at' => Carbon::parse('2024-06-12 10:02:09'),
            'updated_at' => Carbon::parse('2024-06-12 10:02:09')
        ],
        [
            'id' => 4,
            'parent_id' => 0,
            'order' => 4,
            'title' => 'Profile',
            'icon' => 'icon-address-card',
            'uri' => '/profile',
            'created_at' => Carbon::parse('2024-06-12 10:02:09'),
            'updated_at' => Carbon::parse('2024-06-12 10:02:09')
        ],
        [
            'id' => 5,
            'parent_id' => 0,
            'order' => 5,
            'title' => 'My Links',
            'icon' => 'icon-align-justify',
            'uri' => '/mylinks',
            'created_at' => Carbon::parse('2024-06-12 10:02:09'),
            'updated_at' => Carbon::parse('2024-06-12 10:02:09')
        ],
        [
            'id' => 6,
            'parent_id' => 0,
            'order' => 6,
            'title' => 'Force Directed Tree',
            'icon' => 'icon-file-image',
            'uri' => '/force-directed-tree',
            'created_at' => Carbon::parse('2024-06-12 10:02:09'),
            'updated_at' => Carbon::parse('2024-06-12 10:02:09')
        ],
        [
            'id' => 7,
            'parent_id' => 0,
            'order' => 7,
            'title' => 'Settings',
            'icon' => 'icon-tools',
            'uri' => '/settings',
            'created_at' => Carbon::parse('2024-06-12 10:02:09'),
            'updated_at' => Carbon::parse('2024-06-12 10:02:09')
        ],
        [
            'id' => 8,
            'parent_id' => 0,
            'order' => 8,
            'title' => 'About',
            'icon' => 'icon-info',
            'uri' => '/about',
            'created_at' => Carbon::parse('2024-06-12 10:02:09'),
            'updated_at' => Carbon::parse('2024-06-12 10:02:09')
        ]
    ]);

    DB::table('admin_permissions')->insert([
        [ 
            'id' => 1,
            'name' => 'All permissions',
            'slug' => '*',
            'http_path' => '*',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]
    
    ]);

    DB::table('admin_role_users')->insert([
        [ 
            'role_id' => 1,
            'user_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]
    
    ]);

    DB::table('admin_role_menu')->insert([
        [ 
            'role_id' => 1,
            'menu_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]
    
    ]);

    DB::table('admin_role_permissions')->insert([
        [ 
            'role_id' => 1,
            'permission_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]
    
    ]);

    DB::table('admin_users')->insert([
        [ 
            'id' => 1,
            'username' => 'admin',
            'password' => '$2y$12$zxUb/895aZp5eRfjQw2X5OzVuISVT7fL9ZQqHRgT4Gkdu9ca0VzA2',
            'name' => 'Administrator',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]
    
    ]);
    
    }
}
