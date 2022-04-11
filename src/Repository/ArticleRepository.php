<?php

namespace RSSReader\Repository;

use RSSReader\Entity\Article;
use RSSReader\Entity\Resource;

class ArticleRepository
{
    /**
     * @param Resource $resource
     * @param int $page
     * @param int $limit
     * @return Article[]
     */
    public function getArticlesByResource(Resource $resource, int $page, int $limit): array
    {
        $articles = \ORM::forTable('articles')
            ->where('resource_id', $resource->getId())
            ->offset(($page - 1) * $limit)
            ->limit($limit)
            ->findArray();

        return array_map(fn(array $data) => new Article(
            $data['id'],
            $data['url'],
            $data['title'],
            $data['content'],
            $data['resource_id'],
            $data['created_at']
        ), $articles);
    }

    public function saveArticle(Article $dataArticle): bool
    {
        try {
            $article = \ORM::for_table('articles')->create();

            $article->id = $dataArticle->getId();
            $article->title = $dataArticle->getTitle();
            $article->url = $dataArticle->getUrl();
            $article->content = $dataArticle->getContent();
            $article->created_at = $dataArticle->getCreatedAt() !== "" ? $dataArticle->getCreatedAt() : date('d-M-Y H:i:s');
            $article->resource_id = $dataArticle->getResourceId();

            $article->save();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getMaxIdArticle(): int
    {
        $articles = \ORM::for_table('articles')
            ->findArray();

        $maxID = 0;

        foreach ($articles as $article) {
            if ($article['id'] > $maxID) {
                $maxID = $article['id'];
            }
        }

        return $maxID + 1;
    }
}