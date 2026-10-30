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
use Symfony\Component\Finder\SplFileInfo;
use Translation\Extractor\Model\SourceCollection;

trait FormTrait
{
    /**
     * Classes being traversed, innermost last: [end position in file, is form type].
     *
     * @var list<array{int, bool}>
     */
    private array $classStack = [];
    private array $classMap = [];
    private array $symfonyInterfaces = ['FormTypeInterface', 'FormTypeExtensionInterface'];

    /**
     * The visitor is reused for every file: the form type detection of the
     * previous file must not leak to the next one.
     */
    public function init(SourceCollection $collection, SplFileInfo $file): void
    {
        $this->classStack = [];

        parent::init($collection, $file);
    }

    /**
     * Must be called for every node, in traversal order. The enclosing classes
     * are tracked by position because the visitors define their own leaveNode().
     */
    protected function isFormType(Node $node): bool
    {
        // Leave the classes (named or anonymous) that end before this node
        while ($this->classStack && end($this->classStack)[0] < $node->getStartFilePos()) {
            array_pop($this->classStack);
        }

        if ($node instanceof Class_) {
            $this->classStack[] = [$node->getEndFilePos(), $this->implementsFormInterface($node)];
        }

        return $this->classStack ? end($this->classStack)[1] : false;
    }

    private function implementsFormInterface(Class_ $node): bool
    {
        foreach ($this->getAllInterfacesFromNode($node) as $interface) {
            foreach ($this->symfonyInterfaces as $symfonyInterface) {
                if ($interface === $symfonyInterface || str_ends_with($interface, '\\'.$symfonyInterface)) {
                    return true;
                }
            }
        }

        return false;
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
            $reflector = new \ReflectionClass($parentFqcn);
            $filePath = $reflector->getFileName();

            if (!$filePath || !file_exists($filePath)) {
                unset($loading[$parentFqcn]);

                return [];
            }

            /** @phpstan-ignore-next-line */
            $parser = (new ParserFactory())->createForHostVersion();
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
}
