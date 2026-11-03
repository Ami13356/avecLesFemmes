<?php

namespace App\DTO;

/**
 * DTO de sortie : une page de résultats et ce qu'il faut pour la paginer.
 */
final class PaginationDTO
{
    /**
     * @param array<int, mixed> $items les DTO de la page courante
     */
    public function __construct(
        public readonly array $items,
        public readonly int $page,
        public readonly int $nbPages,
        public readonly int $total,
        public readonly int $parPage,
    ) {
    }

    public function aPagePrecedente(): bool
    {
        return $this->page > 1;
    }

    public function aPageSuivante(): bool
    {
        return $this->page < $this->nbPages;
    }
}