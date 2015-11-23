<?php // -*-mode: PHP; coding:iso-8859-2;-*-

// $Id: lang.cs 1029 2009-02-24 19:26:53Z cimorrison $

// This file contains PHP code that specifies language specific strings
// The default strings come from lang.en, and anything in a locale
// specific file will overwrite the default. This is a Czech file.
//
// Translations provided by: "SmEjDiL" <malyl@col.cz>, 
//   "David Krotil" <David.Krotil@mu-sokolov.cz>
//
// This file is PHP code. Treat it as such.

// The charset to use in "Content-type" header
$vocab["charset"]            = "iso-8859-2";

// Used in style.inc
$vocab["mrbs"]               = "MRBS - Rezerva�n� syst�m";

// Used in functions.inc
$vocab["report"]             = "V�pis";
$vocab["admin"]              = "Administr�tor";
$vocab["help"]               = "Pomoc";
$vocab["search"]             = "Hledat";
$vocab["not_php3"]           = "UPOZORN�N�: Toto z�ejm� nen� funk�n� s PHP3";

// Used in day.php
$vocab["bookingsfor"]        = "Objedn�no pro";
$vocab["bookingsforpost"]    = ""; // Goes after the date
$vocab["areas"]              = "Oblasti";
$vocab["daybefore"]          = "Den vzad";
$vocab["dayafter"]           = "Den vp�ed";
$vocab["gototoday"]          = "Dnes";
$vocab["goto"]               = "P�ej�t na";
$vocab["highlight_line"]     = "Ozna�te tuto ��dku";
$vocab["click_to_reserve"]   = "Klepn�te na bu�ku, aby jste provedli rezervaci.";

// Used in trailer.inc
$vocab["viewday"]            = "Dny";
$vocab["viewweek"]           = "T�dny";
$vocab["viewmonth"]          = "M�s�ce ";
$vocab["ppreview"]           = "Pro tisk";

// Used in edit_entry.php
$vocab["addentry"]           = "P�idat z�znam";
$vocab["editentry"]          = "Editovat z�znam";
$vocab["editseries"]         = "Editovat s�rii";
$vocab["namebooker"]         = "Popis instrukce";
$vocab["fulldescription"]    = "Celkov� popis:<br>&nbsp;&nbsp;(Po�et cestuj�c�ch,<br>&nbsp;&nbsp;Obsazeno/Voln� m�sta atd)";
$vocab["date"]               = "Datum";
$vocab["start_date"]         = "Za��tek";
$vocab["end_date"]           = "Konec";
$vocab["time"]               = "�as";
$vocab["period"]             = "Perioda";
$vocab["duration"]           = "Doba trv�n�";
$vocab["seconds"]            = "sekundy";
$vocab["minutes"]            = "minuty";
$vocab["hours"]              = "hodiny";
$vocab["days"]               = "dny";
$vocab["weeks"]              = "v�kendy";
$vocab["years"]              = "roky";
$vocab["periods"]            = "period";
$vocab["all_day"]            = "V�echny dny";
$vocab["type"]               = "Typ";
$vocab["internal"]           = "Voln� m�sta";
$vocab["external"]           = "Obsazeno";
$vocab["save"]               = "Ulo�it";
$vocab["rep_type"]           = "Typ opakov�n�";
$vocab["rep_type_0"]         = "Nikdy";
$vocab["rep_type_1"]         = "Denn�";
$vocab["rep_type_2"]         = "T�dn�";
$vocab["rep_type_3"]         = "M�s��n�";
$vocab["rep_type_4"]         = "Ro�n�";
$vocab["rep_type_5"]         = "M�s��n�, jednou za m�s�c";
$vocab["rep_type_6"]         = "n-t�dn�";
$vocab["rep_end_date"]       = "Konec opakov�n�";
$vocab["rep_rep_day"]        = "Opakovat v den";
$vocab["rep_for_weekly"]     = "(pro (n-)t�dn�)";
$vocab["rep_freq"]           = "Frekvence";
$vocab["rep_num_weeks"]      = "�islo t�dne";
$vocab["rep_for_nweekly"]    = "(pro n-t�dn�)";
$vocab["ctrl_click"]         = "U��t CTRL pro v�b�r v�ce m�stnost�";
$vocab["entryid"]            = "Vstupn� ID ";
$vocab["repeat_id"]          = "ID pro opakov�n�"; 
$vocab["you_have_not_entered"] = "Nevlo�il jste";
$vocab["you_have_not_selected"] = "Nevybral jste";
$vocab["valid_room"]         = "prost�edek.";
$vocab["valid_time_of_day"]  = "platn� �asov� �sek dne.";
$vocab["brief_description"]  = "Kr�tk� popis.";
$vocab["useful_n-weekly_value"] = "pou�iteln� x-t�denn� hodnota.";

// Used in view_entry.php
$vocab["description"]        = "Popis";
$vocab["room"]               = "M�stnost";
$vocab["createdby"]          = "Vytvo�il u�ivatel";
$vocab["lastupdate"]         = "Posledn� zm�na";
$vocab["deleteentry"]        = "Smazat z�znam";
$vocab["deleteseries"]       = "Smazat s�rii";
$vocab["confirmdel"]         = "Jste si jist�\\nsmaz�n�m tohoto z�znamu?\\n\\n";
$vocab["returnprev"]         = "N�vrat na p�edchoz� str�nku";
$vocab["invalid_entry_id"]   = "�patn� ID z�znamu.";
$vocab["invalid_series_id"]  = "�patn� ID skupiny.";

// Used in edit_entry_handler.php
$vocab["error"]              = "Chyba";
$vocab["sched_conflict"]     = "Konflikt p�i pl�nov�n�";
$vocab["conflict"]           = "Nov� rezervace je v konfliktu s jin�m z�znamem";
$vocab["too_may_entrys"]     = "Vybran� volba byla vytvo�ena pro jin� z�znamy.<br>Pros�m vyberte jinou volbu!";
$vocab["returncal"]          = "N�vrat do kalend��e";
$vocab["failed_to_acquire"]  = "Chyba v�hradn�ho p��stupu do datab�ze"; 

// Authentication stuff
$vocab["accessdenied"]       = "P��stup zam�tnut";
$vocab["norights"]           = "Nem�te p��stupov� pr�vo pro zm�nu t�to polo�ky.";
$vocab["please_login"]       = "Pros�m, p�ihla�te se";
$vocab["user_name"]          = "Jm�no";
$vocab["user_password"]      = "Heslo";
$vocab["user_level"]         = "Pr�va";
$vocab["unknown_user"]       = "Nezn�m� u�ivatel";
$vocab["you_are"]            = "Jste";
$vocab["login"]              = "P�ihl�sit se";
$vocab["logoff"]             = "Odhl�sit se";

// Authentication database
$vocab["user_list"]          = "Seznam u�ivatel�";
$vocab["edit_user"]          = "Editovat u�ivatele";
$vocab["delete_user"]        = "Smazat tohoto u�ivatele";
//$vocab["user_name"]         = Use the same as above, for consistency.
//$vocab["user_password"]     = Use the same as above, for consistency.
$vocab["user_email"]         = "Emailov� adresa";
$vocab["password_twice"]     = "Pokud chcete zm�nit heslo, pros�m napi�te ho dvakr�t";
$vocab["passwords_not_eq"]   = "Chyba: Vlo�en� hesla se neshoduj�.";
$vocab["add_new_user"]       = "P�idat nov�ho u�ivatele";
$vocab["action"]             = "Akce";
$vocab["user"]               = "U�ivatel";
$vocab["administrator"]      = "Administr�tor";
$vocab["unknown"]            = "Nezn�m�";
$vocab["ok"]                 = "Ano";
$vocab["show_my_entries"]    = "Klepnout pro zobraz�n� v�ech nadch�zej�c�ch z�znam�";

// Used in search.php
$vocab["invalid_search"]     = "Pr�zdn� nebo neplatn� hledan� �et�zec.";
$vocab["search_results"]     = "V�sledek hled�n� pro";
$vocab["nothing_found"]      = "Nic nenalezeno";
$vocab["records"]            = "Z�znam";
$vocab["through"]            = " skrze ";
$vocab["of"]                 = " o ";
$vocab["previous"]           = "P�edchozi";
$vocab["next"]               = "Dal��";
$vocab["entry"]              = "Z�znam";
$vocab["view"]               = "N�hled";
$vocab["advanced_search"]    = "Roz���en� hled�n�";
$vocab["search_button"]      = "Hledat";
$vocab["search_for"]         = "Hledat co";
$vocab["from"]               = "Od";

// Used in report.php
$vocab["report_on"]          = "V�pis setk�n�";
$vocab["report_start"]       = "V�pis za��tk�";
$vocab["report_end"]         = "V�pis konc�";
$vocab["match_area"]         = "Hledan� oblast";
$vocab["match_room"]         = "Hledan� m�stnost";
$vocab["match_type"]         = "Hledan� typ";
$vocab["ctrl_click_type"]    = "U��t CTRL pro v�b�r v�ce typ�";
$vocab["match_entry"]        = "Hledat v popisu";
$vocab["match_descr"]        = "Hledat v cel�m popisu";
$vocab["include"]            = "Zahrnovat";
$vocab["report_only"]        = "Jen v�pis";
$vocab["summary_only"]       = "Jen p�ehled";
$vocab["report_and_summary"] = "V�pis a p�ehled";
$vocab["summarize_by"]       = "P�ehled od";
$vocab["sum_by_descrip"]     = "Popis instrukce";
$vocab["sum_by_creator"]     = "Tv�rce";
$vocab["entry_found"]        = "nalezeno";
$vocab["entries_found"]      = "nalezeno";
$vocab["summary_header"]     = "P�ehled  (z�znamu) hodiny";
$vocab["summary_header_per"] = "P�ehled  (z�znamu) periody";
$vocab["total"]              = "Celkem";
$vocab["submitquery"]        = "Vytvo�it sestavu";
$vocab["sort_rep"]           = "Se�adit v�pis podle";
$vocab["sort_rep_time"]      = "V�choz� den/�as";
$vocab["rep_dsp"]            = "Zobrazit ve v�pisu";
$vocab["rep_dsp_dur"]        = "Trv�n�";
$vocab["rep_dsp_end"]        = "�as ukon�en�";

// Used in week.php
$vocab["weekbefore"]         = "T�den dozadu";
$vocab["weekafter"]          = "T�den dop�edu";
$vocab["gotothisweek"]       = "Tento t�den";

// Used in month.php
$vocab["monthbefore"]        = "M�s�c dozadu";
$vocab["monthafter"]         = "M�sic dop�edu";
$vocab["gotothismonth"]      = "Tento m�s�c";

// Used in {day week month}.php
$vocab["no_rooms_for_area"]  = "Pro tuto m�stnost nen� definov�na �adn� oblast!";

// Used in admin.php
$vocab["edit"]               = "Editovat";
$vocab["delete"]             = "Smazat";
$vocab["rooms"]              = "M�stnosti";
$vocab["in"]                 = "v";
$vocab["noareas"]            = "��dn� oblasti";
$vocab["addarea"]            = "P�idat oblast";
$vocab["name"]               = "Jm�no";
$vocab["noarea"]             = "Nen� vybr�na ��dn� oblast";
$vocab["browserlang"]        = "Prohl�ec je nastaven k pou�it�";
$vocab["addroom"]            = "P�idat m�stnost";
$vocab["capacity"]           = "Kapacita";
$vocab["norooms"]            = "��dn� m�stnost.";
$vocab["administration"]     = "Administrace";

// Used in edit_area_room.php
$vocab["editarea"]           = "Editovat oblast";
$vocab["change"]             = "Zm�na";
$vocab["backadmin"]          = "N�vrat do administrace";
$vocab["editroomarea"]       = "Editovat popis oblasti nebo m�stnosti";
$vocab["editroom"]           = "Editovat m�stnosti";
$vocab["update_room_failed"] = "Chyba editace m�stnosti: ";
$vocab["error_room"]         = "Chyba: m�stnost ";
$vocab["not_found"]          = " nenalezen";
$vocab["update_area_failed"] = "Chyba editace oblasti: ";
$vocab["error_area"]         = "Chyba: oblast ";
$vocab["room_admin_email"]   = "Email administr�tora m�stnosti";
$vocab["area_admin_email"]   = "Email administr�tora oblasti";
$vocab["invalid_email"]      = "�patn� email!";

// Used in del.php
$vocab["deletefollowing"]    = "Bylo smaz�no rezervov�n�";
$vocab["sure"]               = "Jste si jist�?";
$vocab["YES"]                = "ANO";
$vocab["NO"]                 = "NE";
$vocab["delarea"]            = "Mus�te smazat v�echny m�stnosti v t�to oblasti p�edt�m ne� ji m��ete smazat<p>";

// Used in help.php
$vocab["about_mrbs"]         = "O MRBS";
$vocab["database"]           = "Datab�ze";
$vocab["system"]             = "Syst�m";
$vocab["please_contact"]     = "Pros�m kontaktujte ";
$vocab["for_any_questions"]  = "pokud m�te n�jak� dal�� ot�zky.";

// Used in mysql.inc AND pgsql.inc
$vocab["failed_connect_db"]  = "Fataln� chyba: Nepoda�ilo se p�ipojit do datab�ze";

?>
