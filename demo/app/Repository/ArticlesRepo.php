<?php declare(strict_types=1);

namespace App\Repository;

class ArticlesRepo
{
    public function getList()
    {
        return [
            [
                'id' => 1,
                'title' => 'Article about something',
                'author' => 'John Doe',
                'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse interdum consequat neque, 
                    in elementum nisl porta vel. Sed nunc magna, imperdiet eleifend luctus ac, varius ut nisl. 
                    Pellentesque auctor felis at finibus tristique. Etiam id eros in nulla tincidunt fringilla. 
                    Sed nec leo a nisl.',
            ],
            [
                'id' => 2,
                'title' => 'Article about else...',
                'author' => 'Stephen King',
                'body' => 'In leo lacus, porta venenatis sollicitudin ut, efficitur ut neque. 
                    Pellentesque molestie vestibulum sapien eu feugiat. Suspendisse non est pharetra est 
                    eleifend viverra id a erat. Aenean a orci ultrices, cursus ex nec, lobortis nunc. 
                    Vivamus eleifend auctor augue vitae finibus. Donec ornare, purus quis.',
            ],
        ];
    }

    public function getArticle($id)
    {
        $article = array_reduce($this->getList(), function ($carry, $item) use ($id) {
            if ($item['id'] == $id) {
                $carry = $item;
            }

            return $carry;
        });

        return $article;
    }
}
