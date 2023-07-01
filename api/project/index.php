<?php
    ini_set('display_errors', '0');
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");

    $accessToken = "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";
    $endpoint = "https://api.github.com/graphql";
    $query = '
    query{
        node(id: "PVT_kwDOBpUc684AOB6Y") {
            ... on ProjectV2 {
                items(first: 50) {
                    nodes {
                        ... on ProjectV2Item {
                            content {
                                ... on Issue {
                                    title
                                    body
                                }
                                ... on DraftIssue {
                                    title
                                }
                            }
                            type: fieldValueByName (name: "Typ") {
                                ... on ProjectV2ItemFieldSingleSelectValue {
                                    name
                                }
                            }
                            state: fieldValueByName (name: "Status") {
                                ... on ProjectV2ItemFieldSingleSelectValue {
                                    name
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    ';

    $states = array(
        "Todo" => "open",
        "In Progress" => "wip",
        "Done" => "done"
    );

    $envs = array(
        "INFRASTRUKTUR" => 3,
        "FRONTEND" => 2,
        "BACKEND" => 1
    );

    $data = array ('query' => $query);
    $data = json_encode($data);


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: SwimResults',
        'Authorization: Bearer '.$accessToken,
        'Content-Type: application/json'
    ]);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $content = curl_exec($ch);

    curl_close($ch);



    $d = array();

    $content_data = json_decode($content, TRUE);

    $issue_sum = 0;
    $prog_sum = 0;

    foreach ($content_data["data"]["node"]["items"]["nodes"] as $k => $issue) {
        $issue_sum++;
        $p = 0;
        if (isset($issue["content"]["body"])) {
            $p = intval(str_replace("%", "", $issue["content"]["body"]));
        }
        $d[] = array(
            "title" => $issue["content"]["title"],
            "progress" => $p,
            "type" => strtoupper($issue["type"]["name"]),
            "state" => strtoupper($states[$issue["state"]["name"]])
        );
        $prog_sum += $p;
    }

    function sortByType($a, $b) {
        return ($a["progress"] < $b["progress"]);
    }

    usort($d, "sortByType");

    $json = array(
        "issues" => $issue_sum,
        "progress" => round(($prog_sum / $issue_sum), 5),
        "epics" => $d
    );

    echo(json_encode($json));

?>