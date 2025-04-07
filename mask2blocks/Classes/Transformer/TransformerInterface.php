<?php
declare(strict_types=1);

namespace T3dev\Mask2blocks\Transformer;

/**
 * Interface Transformer
 */
interface TransformerInterface
{
    /**
     * @param array $source
     * @param array $options
     *
     * @return array
     */
    public function transform(array $source, array $options = []): array;
}
