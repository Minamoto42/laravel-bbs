<?php
 
 namespace Database\Factories;
 
 use App\Models\Link;
 use Illuminate\Database\Eloquent\Factories\Factory;
 
 /**
  * @extends Factory<Link>
  */
 class LinkFactory extends Factory
 {
     protected array $links = [
         [
             'title' => 'Stack Overflow',
             'link' => 'https://stackoverflow.com',
         ],
         [
             'title' => 'Reddit - r/programming',
             'link' => 'https://www.reddit.com/r/programming/?rdt=61544',
         ],
         [
             'title' => 'Hacker News',
             'link' => 'https://news.ycombinator.com/',
         ],
         [
             'title' => 'Hack Forums',
             'link' => 'https://hackforums.net/',
         ],
         [
             'title' => 'Welcome to 40k',
             'link' => 'https://warhammer40000.com/',
         ],
         [
             'title' => 'The Laravel Blog ',
             'link' => 'https://blog.laravel.com/',
         ],
     ];
 
     /**
      * Define the model's default state.
      *
      * @return array<string, mixed>
      */
     public function definition(): array
     {
         return array_shift($this->links);
     }
 }