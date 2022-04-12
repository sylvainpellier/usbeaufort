<?php
namespace App\Extension;

use Twig\Environment;

class LambdaExtension extends \DPolac\TwigLambda\LambdaExtension
{

    public function initRuntime(Environment $environment)
    {

    }

    public function getGlobals()
    {
        // TODO: Implement getGlobals() method.
    }

    public function getOperators()
    {
        return [
            [
                '==>' => [
                    'precedence' => 0,
                    'class' => '\DPolac\TwigLambda\NodeExpression\SimpleLambda'
                ],
            ],
            [
                '==>' => [
                    'precedence' => 0,
                    'class' => '\DPolac\TwigLambda\NodeExpression\LambdaWithArguments',
                    'associativity' => \Twig_ExpressionParser::OPERATOR_LEFT
                ],
                ';' => [
                    'precedence' => 5,
                    'class' => '\DPolac\TwigLambda\NodeExpression\Arguments',
                    'associativity' => \Twig_ExpressionParser::OPERATOR_RIGHT
                ],
            ]
        ];
    }

}