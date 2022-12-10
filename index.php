<?php
$jsonTestCases = file_get_contents('testcases.json');
$testCases = json_decode($jsonTestCases);
foreach($testCases as $caseName => $caseData) {
    echo "-----------------------------------<br />";
    echo $caseName . ' wordt getest<br />';
    echo 'expected output: <br />';
    echo str_replace("\n", '<br />', str_replace(' ', '&nbsp;', $caseData->expectedOutput)) . '<br /><br />';

    $input = explode("\n", $caseData->input);

    echo "-----------------------------------<br />";
    echo "actual output:<br />";

    extractOutputFromInput($input);
}

function extractOutputFromInput($lines)
{
    $bottomPositions = explode('  ', $lines[count($lines) - 1]);
    unset($lines[count($lines) - 1]);
    $headerPositions = explode('  ', array_shift($lines));

    $crosses = [];
    foreach ($lines as $index => $line) {
        $crosses[$index] = [];
        foreach (explode('|', $line) as $index2 => $posline) {
            if ($posline == '--') {
                $crosses[$index][] = $index2 - 1;
            }
        }
    }

    $answer = [];
    foreach ($headerPositions as $line => $headerPosition) {
        $currentLine = $line;
        $answer[$line] = $headerPosition;

        for ($i = 0; $i < count($lines); $i++) {
            if (count($crosses[$i]) > 0 && in_array($currentLine - 1, $crosses[$i])) {
                $currentLine--;
                continue;
            }
            if (count($crosses[$i]) > 0 && in_array($currentLine, $crosses[$i])) {
                $currentLine++;
            }
        }

        $answer[$line] .= $bottomPositions[$currentLine];
    }
    foreach ($answer as $ans) {
        echo("$ans<br />");
    }
}
