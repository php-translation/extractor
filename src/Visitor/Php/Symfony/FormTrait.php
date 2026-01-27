<?php

/*
 * This file is part of the PHP Translation package.
 *
 * (c) PHP Translation team <tobias.nyholm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Translation\Extractor\Visitor\Php\Symfony;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PhpParser\PhpVersion;

trait FormTrait
{
    private bool $isFormType = false;
    private array $classMap = [];
    private string $symfonyInterface = 'FormTypeInterface';

    protected function isFormType(Node $node): bool
    {
        if (!$node instanceof Class_) {
            return $this->isFormType;
        }

        $allInterfaces = $this->getAllInterfacesFromNode($node);

        foreach ($allInterfaces as $interface) {
            if ($interface === $this->symfonyInterface
                || str_ends_with($interface, '\\'.$this->symfonyInterface)) {
                $this->isFormType = true;
            }
        }

        return $this->isFormType;
    }

    protected function getAllInterfacesFromNode(Class_ $node): array
    {
        $interfaces = [];

        foreach ($node->implements as $interface) {
            $interfaces[] = $interface->toString();
        }

        if ($node->extends) {
            $parentFqcn = $node->extends->toString();

            $parentInterfaces = $this->loadParentInterfaces($parentFqcn);
            $interfaces = array_merge($interfaces, $parentInterfaces);
        }

        return array_unique($interfaces);
    }

    protected function loadParentInterfaces(string $parentFqcn): array
    {
        $interfaces = [];

        static $loading = [];
        if (isset($loading[$parentFqcn])) {
            return [];
        }
        $loading[$parentFqcn] = true;

        try {
            $filePath = $this->findClassFile($parentFqcn);

            if (!$filePath || !file_exists($filePath)) {
                unset($loading[$parentFqcn]);

                return [];
            }

            /** @phpstan-ignore-next-line */
            $parser = (new ParserFactory())->createForVersion(PhpVersion::fromString('8.1'));
            $code = file_get_contents($filePath);
            $stmts = $parser->parse($code);

            $traverser = new NodeTraverser();
            $traverser->addVisitor(new NameResolver());
            $stmts = $traverser->traverse($stmts);

            foreach ($stmts as $stmt) {
                if ($stmt instanceof Node\Stmt\Namespace_) {
                    foreach ($stmt->stmts as $subStmt) {
                        if ($subStmt instanceof Class_) {
                            $interfaces = array_merge(
                                $interfaces,
                                $this->getAllInterfacesFromNode($subStmt)
                            );
                        }
                    }
                } elseif ($stmt instanceof Class_) {
                    $interfaces = array_merge(
                        $interfaces,
                        $this->getAllInterfacesFromNode($stmt)
                    );
                }
            }
        } catch (\Exception $e) {
        }

        unset($loading[$parentFqcn]);

        return $interfaces;
    }

    private function findClassFile(string $fqcn): ?string
    {
        $autoloadFile = __DIR__.'/../../../../vendor/autoload.php';

        if (!file_exists($autoloadFile)) {
            return null;
        }

        $loader = require $autoloadFile;

        return $loader->findFile($fqcn) ?: null;
    }
}
