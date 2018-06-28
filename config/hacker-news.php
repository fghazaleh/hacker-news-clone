<?php

$app['hacker-news'] = [
    'per_page' => 15,
    'urls' => [
        'top_stories' => 'https://hacker-news.firebaseio.com/v0/topstories.json',
        'new_stories' => 'https://hacker-news.firebaseio.com/v0/newstories.json',
        'ask_stories' => 'https://hacker-news.firebaseio.com/v0/askstories.json',
        'job_stories' => 'https://hacker-news.firebaseio.com/v0/jobstories.json',
        'show_stories' => 'https://hacker-news.firebaseio.com/v0/showstories.json',
        'max_item' => 'https://hacker-news.firebaseio.com/v0/maxitem.json',
        'item' => 'https://hacker-news.firebaseio.com/v0/item/%d.json',
    ]
];
