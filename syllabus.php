<?php
function flexiEsc($value){return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');}
function flexiItems($items){if(!is_array($items))return ''; $html='<ul class="styled-list">';foreach($items as $item){if(is_scalar($item))$html.='<li>'.flexiEsc($item).'</li>';}$html.='</ul>';return $html;}
function flexiValueText($value){if(is_array($value)){return flexiEsc(implode(', ',array_map('strval',$value)));}return flexiEsc($value);}
function flexiRenderSyllabus($full,$exam,$meta){
 $data=isset($full['syllabus'])&&is_array($full['syllabus'])?$full['syllabus']:$full;
 $html='<div class="subject-title-card"><span class="badge-exam">'.($exam==='jamb'?'JAMB / UTME':'WAEC / WASSCE').'</span><h2>'.flexiEsc($data['subject']??$data['document_title']??$meta['name']??'Subject').' Syllabus</h2>';
 if(!empty($meta['desc']))$html.='<p class="subject-desc">'.flexiEsc($meta['desc']).'</p>';
 if(!empty($data['preamble']))$html.='<p style="margin-top:.4rem"><strong>Preamble:</strong> '.flexiEsc($data['preamble']).'</p>';
 $html.='</div>';
 $objectives=$data['general_objectives']??$data['objectives']??$data['aims']??[];
 if(is_array($objectives)&&$objectives)$html.='<div class="section-card"><h3 class="section-title">'.($exam==='waec'?'Course Objectives & Aims':'General Objectives').'</h3>'.flexiItems($objectives).'</div>';
 if(!empty($data['assessment_objectives'])&&is_array($data['assessment_objectives'])){$html.='<div class="section-card"><h3 class="section-title">Assessment Objectives</h3>';foreach($data['assessment_objectives'] as $key=>$items){$html.='<h4 class="text-capitalize">'.flexiEsc(str_replace('_',' ',$key)).'</h4>'.flexiItems($items);}$html.='</div>';}
 $scheme=$data['examination_scheme']??$data['structure_of_the_examination']??null;
 if($scheme){$html.='<div class="section-card"><h3 class="section-title">Examination Scheme</h3>';if(is_string($scheme))$html.='<p>'.flexiEsc($scheme).'</p>';elseif(is_array($scheme)){if(!empty($scheme['summary']))$html.='<p>'.flexiEsc($scheme['summary']).'</p>';if(!empty($scheme['papers'])&&is_array($scheme['papers'])){$html.='<div style="overflow-x:auto"><table><thead><tr><th>Paper</th><th>Title</th><th>Duration</th><th>Marks</th><th>Details</th></tr></thead><tbody>';foreach($scheme['papers'] as $paper)$html.='<tr><td><strong>'.flexiEsc($paper['paper']??'').'</strong></td><td>'.flexiEsc($paper['title']??'').'</td><td>'.flexiEsc($paper['duration']??'').'</td><td>'.flexiEsc($paper['marks']??'').'</td><td>'.flexiEsc($paper['details']??'').'</td></tr>';$html.='</tbody></table></div>';}else{foreach($scheme as $key=>$value){if($key==='summary')continue;$html.='<h4>'.flexiEsc(strtoupper(str_replace('_',' ',$key))).'</h4>';if(is_scalar($value))$html.='<p>'.flexiEsc($value).'</p>';elseif(is_array($value)){foreach($value as $k=>$v){if(is_scalar($v))$html.='<p><strong>'.flexiEsc(ucfirst((string)$k)).':</strong> '.flexiEsc($v).'</p>';}}}}}$html.='</div>';}
 $structure=$data['structure']??$data['topics']??$data['sections']??$data['syllabus_content']??null;
 $detailed=$data['detailed_syllabus']??null;
 if($structure&&is_array($structure)){$html.='<div class="section-card"><h3 class="section-title">'.($exam==='jamb'?'Syllabus Structure & Topics':'Syllabus Topics').'</h3>';foreach($structure as $section){if(!is_array($section))continue;$title=$section['section']??$section['topic']??$section['title']??'Section';$notes=$section['contents_notes']??$section['notes']??$section['content']??$section['details']??null;$html.='<div style="margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border)"><h4 style="color:var(--flexi-blue);margin-bottom:.4rem">'.flexiEsc($title).'</h4>';if($notes)$html.='<p style="margin:.4rem 0;color:#334155;font-size:.95rem"><strong>Contents & Notes:</strong> '.flexiValueText($notes).'</p>';$sub=$section['objectives']??$section['learning_objectives']??[];if($sub)$html.='<div style="margin-top:.6rem"><strong>Learning Objectives:</strong>'.flexiItems($sub).'</div>';$html.='</div>';}$html.='</div>';}
 if($detailed&&is_array($detailed)){$html.='<div class="section-card"><h3 class="section-title">Detailed Syllabus Content</h3>';foreach($detailed as $section){if(!is_array($section))continue;$title=$section['section_name']??$section['section_title']??$section['title']??$section['topic']??'Section';$html.='<h4 style="color:var(--flexi-blue);margin-top:1rem">'.flexiEsc($title).'</h4>';if(!empty($section['contents']))$html.=flexiItems($section['contents']);if(!empty($section['topics'])&&is_array($section['topics']))foreach($section['topics'] as $topic){if(!is_array($topic))continue;$html.='<details class="topic-block" open><summary class="topic-header">'.flexiEsc($topic['title']??$topic['topics']??'Topic').'</summary><div class="topic-body">'.(!empty($topic['notes'])?'<p>'.flexiEsc($topic['notes']).'</p>':'').(!empty($topic['details'])?flexiItems($topic['details']):'').'</div></details>';}}$html.='</div>';}
 $texts=$data['selected_texts']??$data['prescribed_texts']??$data['literature_texts']??null;
 if(is_array($texts)){$html.='<div class="section-card"><h3 class="section-title">Prescribed / Selected Texts</h3>';foreach($texts as $genre=>$groups){$html.='<h4 class="text-capitalize">'.flexiEsc(str_replace('_',' ',$genre)).'</h4>';if(is_array($groups)){if(array_is_list($groups))$html.=flexiItems($groups);else foreach($groups as $cat=>$books){$html.='<strong>'.flexiEsc(str_replace('_',' ',$cat)).':</strong>'.flexiItems($books);}}}$html.='</div>';}
 $reading=$data['recommended_texts']??$data['reading_list']??$data['recommended_books']??null;
 if(is_array($reading)){$html.='<div class="section-card"><h3 class="section-title">Recommended Reading List</h3><ol class="styled-list">';foreach($reading as $book){if(is_scalar($book))$html.='<li>'.flexiEsc($book).'</li>';elseif(is_array($book))foreach($book as $entry)if(is_scalar($entry))$html.='<li>'.flexiEsc($entry).'</li>';}$html.='</ol></div>';}
 return $html;
}
$registryFile=__DIR__.'/data/syllabus-subject-registry.json';
$subjectRegistry=is_file($registryFile)?json_decode(file_get_contents($registryFile),true):[];
if(!is_array($subjectRegistry))$subjectRegistry=[];
$examType=isset($_GET['exam'])&&in_array(strtolower($_GET['exam']),['jamb','waec'],true)?strtolower($_GET['exam']):'jamb';
$subjectList=$subjectRegistry[$examType]??[];
$subjectId=isset($_GET['subject'])?(string)$_GET['subject']:'';
$selectedMeta=null;foreach($subjectList as $entry){if(($entry['id']??'')===$subjectId){$selectedMeta=$entry;break;}}
if(!$selectedMeta&&$subjectList){$selectedMeta=$subjectList[0];$subjectId=$selectedMeta['id'];}
$serverSyllabusHtml='<div class="section-card"><p>No syllabus data is currently available for this subject.</p></div>';
if($selectedMeta&&isset($selectedMeta['path'])){
 $relativePath=$selectedMeta['path'];
 if(strpos($relativePath,'data/')===0&&strpos($relativePath,'..')===false){
  $filePath=__DIR__.'/'.$relativePath;
  if(is_file($filePath)){$decoded=json_decode(file_get_contents($filePath),true);if(is_array($decoded))$serverSyllabusHtml=flexiRenderSyllabus($decoded,$examType,$selectedMeta);}
 }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flexi Educational Consult - Syllabus Portal</title>
  <style>
    :root {
      --blue: #003366;
      --green: #2E8B57;
      /* Flexi Educational Consult Brand Colors */
      --flexi-blue: #0A2540;
      --flexi-blue-light: #1E3A8A;
      --flexi-green: #10B981;
      --flexi-green-dark: #059669;
      --bg: #F8FAFC;
      --card-bg: #FFFFFF;
      --text: #1E293B;
      --border: #E2E8F0;
    }

    * { box-sizing: border-box; }

    body {
      font-family: system-ui, -apple-system, sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.6;
      margin: 0;
      padding: 0;
    }

    

    .container {
      max-width: 950px;
      margin: 0 auto;
      padding: 1.25rem 1.5rem;
    }

    /* Portal Controls */
    .portal-controls {
      background: var(--card-bg);
      padding: 1rem;
      border-radius: 10px;
      margin-bottom: 1.25rem;
      border: 1px solid var(--border);
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .btn-group {
      display: flex;
      gap: 0.75rem;
      margin-bottom: 0.85rem;
    }

    .btn-toggle {
      flex: 1;
      padding: 0.65rem 1rem;
      border: 2px solid var(--flexi-blue);
      background: transparent;
      color: var(--flexi-blue);
      font-weight: 700;
      font-size: 0.95rem;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .btn-toggle:hover {
      background: rgba(10, 37, 64, 0.05);
    }

    .btn-toggle.active {
      background: var(--flexi-blue);
      color: white;
    }

/* Fixed Subject Select Container */
.subject-select-wrap {
  display: flex;
  flex-direction: column; /* Stacks label & dropdown vertically on mobile */
  align-items: stretch;
  gap: 0.4rem;
  width: 100%;
}

/* Ensure label doesn't wrap weirdly */
.subject-select-wrap label {
  font-size: 0.95rem;
  white-space: nowrap;
}

/* Prevent select element from breaking out of its container */
select {
  width: 100%;
  max-width: 100%;
  box-sizing: border-box;
  padding: 0.65rem 0.85rem;
  border-radius: 6px;
  border: 1px solid var(--border);
  font-size: 0.95rem;
  background: var(--bg);
  color: var(--text);
  text-overflow: ellipsis; /* Truncates super long text smoothly */
  overflow: hidden;
  white-space: nowrap;
}

/* Force long file paths/urls inside code tags to break onto new lines */
code {
  word-break: break-word;
  overflow-wrap: anywhere;
}
@media (min-width: 600px) {
  .subject-select-wrap {
    flex-direction: row;
    align-items: center;
  }
}



    /* Content Cards & Containers */
    .subject-title-card {
      background: var(--card-bg);
      border-left: 5px solid var(--flexi-green);
      padding: 1.25rem;
      border-radius: 8px;
      margin-bottom: 1.25rem;
      border-top: 1px solid var(--border);
      border-right: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
    }

    .subject-title-card h2 {
      margin: 0 0 0.25rem 0;
      color: var(--flexi-blue);
    }

    .subject-desc {
      margin: 0.25rem 0 0;
      font-size: 0.9rem;
      color: #64748B;
    }

    .badge-exam {
      display: inline-block;
      background: var(--flexi-green);
      color: white;
      padding: 0.15rem 0.5rem;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      margin-bottom: 0.4rem;
    }

    .section-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 1.25rem;
      margin-bottom: 1.25rem;
    }

    .section-title {
      color: var(--flexi-blue);
      font-size: 1.2rem;
      margin-top: 0;
      border-bottom: 2px solid var(--bg);
      padding-bottom: 0.4rem;
    }

    .styled-list { padding-left: 1.25rem; margin: 0.5rem 0; }
    .styled-list li { margin-bottom: 0.4rem; }

    table { width: 100%; border-collapse: collapse; margin-top: 0.75rem; }
    th, td { border: 1px solid var(--border); padding: 0.65rem; text-align: left; }
    th { background: #F1F5F9; color: var(--flexi-blue); }

    .topic-block {
      border: 1px solid var(--border);
      border-radius: 8px;
      margin-bottom: 0.85rem;
      overflow: hidden;
    }

    .topic-header {
      background: #F8FAFC;
      padding: 0.65rem 0.85rem;
      font-weight: 600;
      cursor: pointer;
    }

    .topic-number {
      background: var(--flexi-green);
      color: white;
      padding: 0.15rem 0.4rem;
      border-radius: 4px;
      font-size: 0.8rem;
      margin-right: 0.4rem;
    }

    .topic-body { padding: 0.85rem; border-top: 1px solid var(--border); }

    .subsection {
      margin-top: 0.85rem;
      padding-left: 0.85rem;
      border-left: 3px solid var(--flexi-green);
    }

    .subsection-title { font-weight: 600; color: var(--flexi-blue); margin-bottom: 0.25rem; }

    .text-capitalize { text-transform: capitalize; }

    /* MODERN FOOTER STYLES */
.footer {
    background: linear-gradient(135deg, #011627 0%, #032038 100%);
    color: #e2e8f0;
    padding: 60px 20px 30px 20px;
    margin-top: 60px;
    border-top: 4px solid var(--green, #22c55e);
    font-size: 14px;
}

.footer-grid {
    display: grid;
    grid-template-columns: 1.8fr 1.2fr 1.3fr 1fr;
    gap: 35px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Headings */
.footer h4 {
    color: var(--yellow, #fbbf24);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin: 0 0 16px 0;
    position: relative;
    padding-bottom: 6px;
}

.footer h4::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 24px;
    height: 2px;
    background: var(--yellow, #fbbf24);
    opacity: 0.7;
    border-radius: 2px;
}

.footer-about {
    line-height: 1.7;
    color: #94a3b8;
    margin: 0;
}

/* Links List */
.footer-links-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links-list li {
    margin-bottom: 10px;
}

.footer a {
    color: #cbd5e0;
    text-decoration: none;
    transition: all 0.25s ease;
}

.footer-links-list a:hover {
    color: var(--yellow, #fbbf24);
    transform: translateX(4px);
    display: inline-block;
}

/* WhatsApp Channel Text Link */
.whatsapp-channel-link {
    color: #25D366 !important;
    font-weight: 600;
    display: inline-block;
    transition: transform 0.25s ease, opacity 0.25s ease;
}

.whatsapp-channel-link:hover {
    opacity: 0.85;
    transform: translateX(4px);
}

/* Contacts & Emails Subheadings */
.contact-group {
    margin-bottom: 16px;
}

.contact-group h5 {
    color: #ffffff;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin: 0 0 6px 0;
    opacity: 0.9;
}

.contact-group a {
    display: block;
    color: #94a3b8;
    font-size: 0.88rem;
    margin-bottom: 4px;
}

.contact-group a:hover {
    color: #ffffff;
}

/* Bottom Bar */
.footer-bottom {
    max-width: 1200px;
    margin: 40px auto 0 auto;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    text-align: center;
    font-size: 13px;
    color: #64748b;
}

/* Responsive Styles */
@media (max-width: 900px) {
    .footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
}

@media (max-width: 550px) {
    .footer-grid {
        grid-template-columns: 1fr;
        gap: 28px;
    }
    }
/* Homepage header and navigation */
    header {
        background: var(--blue);
        color: white;

        height: 52px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 0 20px;

        border-bottom: 3px solid var(--green);

        position: sticky;
        top: 0;

        z-index: 1000;
    }


    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }


    .logo-img {
        height: 34px;
        width: 34px;

        object-fit: contain;

        border-radius: 4px;
    }


    .brand-name {
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.2px;
    }


    /* ========================================================
       MENU
       ======================================================== */

    .menu-container {
        position: relative;
    }


    .menu-btn {
        width: 38px;
        height: 38px;

        cursor: pointer;

        background: rgba(255,255,255,.06);

        border: 1px solid rgba(255,255,255,.32);

        border-radius: 8px;

        display: flex;

        align-items: center;

        justify-content: center;

        color: white;

        transition: background .2s ease, transform .2s ease;
    }


    .menu-btn:hover {
        background: rgba(255,255,255,.14);
        transform: translateY(-1px);
    }


    .menu-svg {
        width: 21px;
        height: 21px;
        display: block;
    }


    .square-menu {
        display: none;

        position: absolute;

        top: 48px;
        right: 0;

        width: 300px;

        background: linear-gradient(145deg, #003366 0%, #075a55 52%, #2E8B57 100%);

        border: 2px solid var(--green);

        border-radius: 10px;

        z-index: 2000;

        box-shadow:
            0 12px 32px rgba(0,0,0,.35);

        overflow: hidden;
    }


    .square-menu.menu-open {
        display: block;
        animation: menuDrop .18s ease-out;
        transform-origin: top right;
    }


    @keyframes menuDrop {
        from {
            opacity: 0;
            transform: translateY(-6px) scale(.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }


    .square-menu a {
        display: flex;

        align-items: center;

        gap: 12px;

        padding: 13px 15px;

        color: white;

        text-decoration: none;

        font-size: 14px;

        border-bottom:
            1px solid rgba(255,255,255,.1);

        transition: background 0.15s, padding-left 0.15s;
    }


    .square-menu a:hover {
        background: rgba(255,255,255,0.12);
        padding-left: 19px;
    }


    .square-menu a:last-child {
        border-bottom: none;
    }


    .menu-icon {
        width: 21px;
        height: 21px;
        flex: 0 0 21px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,.96);
    }


    .menu-icon svg {
        width: 20px;
        height: 20px;
        display: block;
    }


    .menu-label {
        min-width: 0;
        line-height: 1.35;
    }


    .study-menu-toggle {
        width: 100%;
        min-height: 46px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 15px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border: 0;
        border-bottom: 1px solid rgba(255,255,255,.1);
        background: transparent;
        font-family: inherit;
        cursor: pointer;
        text-align: left;
        transition: background .15s ease, padding-left .15s ease;
    }

    .study-menu-toggle:hover {
        background: rgba(255,255,255,0.12);
        padding-left: 19px;
    }

    .study-menu-toggle .menu-icon {
        width: 21px;
        height: 21px;
        flex: 0 0 21px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,.96);
    }

    .study-menu-toggle .menu-icon svg {
        width: 20px;
        height: 20px;
        display: block;
    }

    .study-chevron {
        margin-left: auto;
        width: 17px;
        height: 17px;
        flex: 0 0 17px;
        transition: transform .2s ease;
    }

    .study-menu-toggle[aria-expanded="true"] .study-chevron {
        transform: rotate(180deg);
    }

    .study-submenu {
        display: none;
        margin: 0 8px 5px 34px;
        padding: 4px;
        border-left: 1px solid rgba(255,255,255,.20);
        background: rgba(0,0,0,.10);
        border-radius: 0 8px 8px 0;
    }

    .study-submenu.open {
        display: block;
    }

    .study-submenu a {
        min-height: 40px;
        padding: 9px 10px;
        font-size: 13px;
        font-weight: 500;
        border-bottom: 1px solid rgba(255,255,255,.08);
    }

    .study-submenu a:last-child {
        border-bottom: none;
    }

    .study-submenu .menu-icon {
        width: 18px;
        height: 18px;
        flex-basis: 18px;
    }

    .study-submenu .menu-icon svg {
        width: 18px;
        height: 18px;
    }



        
</style>
</head>
<body>

  <!-- Flexi Brand Header (~0.8cm Height) -->
  <header>

    <div class="header-left">

        <img
            src="https://i.postimg.cc/0Qm3PLw5/1771700279759-2.jpg"
            alt="Flexi Educational Consult Official Logo"
            class="logo-img">

        <span class="brand-name">
            Flexi Educational Consult
        </span>

    </div>


    <div class="menu-container">

        <button
            class="menu-btn"
            onclick="toggleMenu()"
            aria-label="Toggle Navigation Menu">

            <svg class="menu-svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M4 6h16M4 12h16M4 18h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>

        </button>


        <div
            class="square-menu"
            id="squareMenu">

            <a href="/index.php">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-8.5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg></span>
                <span class="menu-label">Home</span>
            </a>

            <a href="/videos.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M4 18.5A2.5 2.5 0 0 1 6.5 16H20" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M10 7l5 3-5 3V7z" fill="currentColor"/></svg></span>
                <span class="menu-label">Watch Video Lessons</span>
            </a>

            <button
                type="button"
                class="study-menu-toggle"
                id="study-menu-toggle"
                onclick="toggleStudyMenu(event)"
                aria-expanded="false"
                aria-controls="study-submenu">
                <span class="menu-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M4 5.5V19M8 7h8M8 11h8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </span>
                <span class="menu-label">Study</span>
                <svg class="study-chevron" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m6 9 6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="study-submenu" id="study-submenu">
                <a href="/study.php">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M4 5.5V19" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="menu-label">Past Questions</span>
                </a>
                <a href="/novel.php">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M5 4.5A2.5 2.5 0 0 1 7.5 2H19v18H7.5A2.5 2.5 0 0 0 5 22V4.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M5 4.5V19M9 7h6M9 11h7M9 15h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="menu-label">Novels</span>
                </a>
                <a href="/scholarship.php">
                    <span class="menu-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 8.5 12 4l9 4.5-9 4.5-9-4.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M6 11.5V16c2.8 2.2 9.2 2.2 12 0v-4.5M21 9v5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="menu-label">Scholarships</span>
                </a>
            </div>

            <a href="/download-app.php">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3v11" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M7.5 10.5L12 15l4.5-4.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 20h14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Download Flexi</span>
            </a>

            <a href="/syllabus.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v18H6.5A2.5 2.5 0 0 0 4 22V4.5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8 7h8M8 11h8M8 15h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Access the JAMB and WAEC syllabus here</span>
            </a>

            <a href="/brochure.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M14 3v5h5M8 12h8M8 16h6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Access JAMB Brochure</span>
            </a>

            <a href="/cbt.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="13" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M8 21h8M12 17v4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">CBT Simulator</span>
            </a>

            <a href="/groups.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="17" cy="9" r="2.5" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0M15 14.5a4.5 4.5 0 0 1 5 5.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Classroom (Groups and chats)</span>
            </a>

            <a href="/purchase.php">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M3 10h18M7 15h4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Purchase Scratch Cards</span>
            </a>

            <a href="/pdf.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 3h9l4 4v14H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M14 3v5h5M8 14h8M8 17h6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">Get your PDFs from here</span>
            </a>

            <a href="/location.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-12A7 7 0 0 0 5 9c0 5.8 7 12 7 12z" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="9" r="2.3" fill="none" stroke="currentColor" stroke-width="1.8"/></svg></span>
                <span class="menu-label">Tutorial Centers Near You</span>
            </a>

            <a href="/profile.html">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M5 20a7 7 0 0 1 14 0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span class="menu-label">User Profile</span>
            </a>

            <a
                href="#"
                id="auth-menu-btn">
                <span class="menu-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M10 4h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M4 12h10M10 8l4 4-4 4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                <span class="menu-label">Login</span>
            </a>

        </div>

    </div>

</header>

  <div class="container">
  <form method="get" action="syllabus.php" class="portal-controls">
    <div class="btn-group">
      <button id="btn-jamb" class="btn-toggle <?= $examType==='jamb'?'active':'' ?>" type="button" onclick="window.location.href='syllabus.php?exam=jamb'">Check JAMB Syllabus</button>
      <button id="btn-waec" class="btn-toggle <?= $examType==='waec'?'active':'' ?>" type="button" onclick="window.location.href='syllabus.php?exam=waec'">Check WAEC Syllabus</button>
    </div>
    <div class="subject-select-wrap">
      <label for="subjectSelect"><strong>Select Subject:</strong></label>
      <input type="hidden" name="exam" value="<?= flexiEsc($examType) ?>">
      <select id="subjectSelect" name="subject" onchange="this.form.submit()">
        <?php foreach($subjectList as $item): ?><option value="<?= flexiEsc($item['id']) ?>" <?= $item['id']===$subjectId?'selected':'' ?>><?= flexiEsc($item['name']) ?></option><?php endforeach; ?>
      </select>
    </div>
  </form>
  <div id="syllabus-app"><?= $serverSyllabusHtml ?></div>
</div>

<footer class="footer">
    <div class="footer-grid">
        <!-- Column 1: About -->
        <div class="footer-col">
            <p class="footer-about">
               We empower Nigerian students with admission updates, CBT preparation, tutorials, past questions in PDF, and premium educational support.
            </p>
        </div>

        <!-- Column 2: Quick Links -->
        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul class="footer-links-list">
                <li><a href="index.php">Home</a></li>
                <li><a href="/about-us.php">About Us</a></li>
                <li><a href="https://elearning.flexieduconsult.com.ng" target="_blank" rel="noopener">WhatsApp Masterclass (E-Learning)</a></li>
                <li><a href="syllabus.php">Access the JAMB/WAEC syllabus</a></li>
                <li><a href="brochure.php">Access JAMB Brochure</a></li>
                <li><a href="videos.php">Video Lessons</a></li>
                <li><a href="pdf.php">Past Questions & PDFs</a></li>
                <li><a href="cbt.php">CBT Simulator</a></li>
                <li><a href="classroom.html">Classroom</a></li>
                <li><a href="location.html">Tutorial Centres</a></li>
            </ul>
        </div>

        <!-- Column 3: Support & Community -->
        <div class="footer-col">
            <h4>Support & Community</h4>
            
            <div class="contact-group">
                <a href="https://whatsapp.com/channel/0029Vb6Lhoc3rZZW8SRooE3u" 
                   target="_blank" 
                   class="whatsapp-channel-link">
                   Join our WhatsApp Channel
                </a>
            </div>

            <div class="contact-group">
                <h5>Contact Us</h5>
                <a href="tel:+2349034159839">(+234) 903 415 9839</a>
                <a href="tel:+2347033855206">(+234) 703 385 5206</a>
            </div>

            <div class="contact-group">
                <h5>Email Us</h5>
                <a href="mailto:support@flexieduconsult.com.ng">support@flexieduconsult.com.ng</a>
                <a href="mailto:info@flexieduconsult.com.ng">info@flexieduconsult.com.ng</a>
            </div>
        </div>

        <!-- Column 4: Social Links -->
        <div class="footer-col social-links">
            <h4>Follow Us</h4>
            <ul class="footer-links-list">
                <li><a href="https://www.facebook.com/profile.php?id=61589793118693" target="_blank">Facebook @flexieduconsult</a></li>
                <li><a href="https://instagram.com/flexieduconsult2000" target="_blank">Instagram @flexieduconsult2000</a></li>
                <li><a href="https://www.tiktok.com/@flexieduconsult" target="_blank">TikTok @flexieduconsult</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        &copy; <?= date("Y") ?> Flexi Educational Consult. All Rights Reserved.
    </div>
  </footer>
<script>
function toggleMenu(){document.getElementById('squareMenu')?.classList.toggle('menu-open');}
function toggleStudyMenu(event){if(event)event.preventDefault();const btn=document.getElementById('study-menu-toggle');const sub=document.getElementById('study-submenu');if(!btn||!sub)return;const open=btn.getAttribute('aria-expanded')!=='true';btn.setAttribute('aria-expanded',open?'true':'false');sub.classList.toggle('open',open);}
document.addEventListener('click',function(event){const menu=document.getElementById('squareMenu');const wrap=document.querySelector('.menu-container');if(menu&&wrap&&!wrap.contains(event.target))menu.classList.remove('menu-open');});
</script>
</body>
</html>
