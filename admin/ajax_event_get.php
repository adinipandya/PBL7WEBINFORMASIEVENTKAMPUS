<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/../config/db.php";

if (empty($_SESSION["logged_in"]) || (($_SESSION["role"] ?? "") !== "admin")) {
  http_response_code(403);
  echo json_encode(["ok"=>false, "message"=>"Forbidden"]);
  exit;
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id <= 0) {
  http_response_code(400);
  echo json_encode(["ok"=>false, "message"=>"Invalid id"]);
  exit;
}

$stmt = $conn->prepare("
  SELECT e.*, c.slug AS category_slug, c.name AS category_name
  FROM events e
  JOIN event_categories c ON c.id = e.category_id
  WHERE e.id = ?
  LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
  http_response_code(404);
  echo json_encode(["ok"=>false, "message"=>"Event not found"]);
  exit;
}

$slug = $event["category_slug"];
$details = [];

if ($slug === "seminar") {
  $d = $conn->query("SELECT * FROM event_seminar_details WHERE event_id=$id LIMIT 1")->fetch_assoc();
  $sp = $conn->query("SELECT name, title_role FROM event_speakers WHERE event_id=$id ORDER BY sort_order ASC, id ASC")->fetch_all(MYSQLI_ASSOC);

  $speakersText = [];
  foreach ($sp as $x) $speakersText[] = trim($x["name"])." | ".trim($x["title_role"] ?? "");

  $details = [
    "seminar_theme" => $d["theme"] ?? "",
    "seminar_quota" => $d["quota"] ?? "",
    "seminar_reg"   => $d["registration_link"] ?? "",
    "seminar_speakers" => implode("\n", $speakersText),
  ];
}

if ($slug === "lokakarya") {
  $d = $conn->query("SELECT * FROM event_workshop_details WHERE event_id=$id LIMIT 1")->fetch_assoc();
  $ins = $conn->query("SELECT name, title_role FROM event_instructors WHERE event_id=$id ORDER BY sort_order ASC, id ASC")->fetch_all(MYSQLI_ASSOC);

  $instText = [];
  foreach ($ins as $x) $instText[] = trim($x["name"])." | ".trim($x["title_role"] ?? "");

  $details = [
    "workshop_theme" => $d["theme"] ?? "",
    "workshop_tools" => $d["tools_required"] ?? "",
    "workshop_quota" => $d["quota"] ?? "",
    "workshop_reg"   => $d["registration_link"] ?? "",
    "workshop_instructors" => implode("\n", $instText),
  ];
}

if ($slug === "kompetisi") {
  $d = $conn->query("SELECT * FROM event_competition_details WHERE event_id=$id LIMIT 1")->fetch_assoc();
  $j  = $conn->query("SELECT name, title_role FROM event_judges WHERE event_id=$id ORDER BY sort_order ASC, id ASC")->fetch_all(MYSQLI_ASSOC);
  $rq = $conn->query("SELECT requirement_text FROM event_competition_requirements WHERE event_id=$id ORDER BY sort_order ASC, id ASC")->fetch_all(MYSQLI_ASSOC);

  $judgesText = [];
  foreach ($j as $x) $judgesText[] = trim($x["name"])." | ".trim($x["title_role"] ?? "");

  $reqText = [];
  foreach ($rq as $x) $reqText[] = trim($x["requirement_text"]);

  $details = [
    "comp_theme" => $d["theme"] ?? "",
    "comp_prize" => $d["prize"] ?? "",
    "comp_deadline" => $d["registration_deadline"] ?? "",
    "comp_rules" => $d["rules"] ?? "",
    "comp_judges" => implode("\n", $judgesText),
    "comp_requirements" => implode("\n", $reqText),
  ];
}

if ($slug === "festival") {
  $d = $conn->query("SELECT * FROM event_festival_details WHERE event_id=$id LIMIT 1")->fetch_assoc();
  $l = $conn->query("SELECT item_name, item_note FROM event_festival_lineups WHERE event_id=$id ORDER BY sort_order ASC, id ASC")->fetch_all(MYSQLI_ASSOC);
  $rd= $conn->query("SELECT start_time, end_time, activity FROM event_festival_rundowns WHERE event_id=$id ORDER BY sort_order ASC, id ASC")->fetch_all(MYSQLI_ASSOC);

  $lineText = [];
  foreach ($l as $x) $lineText[] = trim($x["item_name"])." | ".trim($x["item_note"] ?? "");

  $runText = [];
  foreach ($rd as $x) {
    $st = $x["start_time"] ? substr($x["start_time"],0,5) : "";
    $et = $x["end_time"] ? substr($x["end_time"],0,5) : "";
    $range = ($st || $et) ? ($st."-".$et) : "-";
    $runText[] = trim($range)." | ".trim($x["activity"]);
  }

  $details = [
    "fest_theme" => $d["theme"] ?? "",
    "fest_ticketed" => (string)($d["is_ticketed"] ?? "0"),
    "fest_ticket_price" => $d["ticket_price"] ?? "",
    "fest_area_note" => $d["area_note"] ?? "",
    "fest_lineups" => implode("\n", $lineText),
    "fest_rundown" => implode("\n", $runText),
  ];
}

echo json_encode([
  "ok" => true,
  "event" => [
    "id" => (int)$event["id"],
    "category_id" => (int)$event["category_id"],
    "category_slug" => $event["category_slug"],
    "title" => $event["title"] ?? "",
    "description" => $event["description"] ?? "",
    "event_date" => $event["event_date"] ?? "",
    "start_time" => $event["start_time"] ? substr($event["start_time"],0,5) : "",
    "end_time" => $event["end_time"] ? substr($event["end_time"],0,5) : "",
    "location" => $event["location"] ?? "",
    "contact" => $event["contact"] ?? "",
    "attachment" => $event["attachment"] ?? "",
  ],
  "details" => $details
], JSON_UNESCAPED_UNICODE);
