use statuspeople_spam;

CREATE TEMPORARY TABLE IF NOT EXISTS temp_user_info (INDEX t_idx (twitterid), INDEX l_idx(live)) AS (SELECT * FROM spsp_user_info);

SELECT COUNT(*) FROM temp_user_info;
SELECT LOCALTIMESTAMP() AS 'Time';

CREATE TEMPORARY TABLE IF NOT EXISTS temp_spam_scores (INDEX t_idx (twitterid), INDEX l_idx(live)) AS (SELECT * FROM spsp_spam_scores);

SELECT COUNT(*) FROM temp_spam_scores;
SELECT LOCALTIMESTAMP() AS 'Time';

use statuspeople_spam_api;

DROP TABLE IF EXISTS stpsa_spam_scores_temp;

CREATE TABLE stpsa_spam_scores_temp LIKE stpsa_spam_scores;

DESCRIBE stpsa_spam_scores_temp;
SELECT LOCALTIMESTAMP() AS 'Time';

INSERT INTO stpsa_spam_scores_temp
SELECT ui.twitterid, ui.screen_name, ui.avatar, s.spam, s.potential, s.checks, s.followers, 
s.updated AS score_date, s.live, '1' AS type, UNIX_TIMESTAMP() AS created 
FROM statuspeople_spam.temp_user_info AS ui 
JOIN statuspeople_spam.temp_spam_scores AS s ON ui.twitterid = s.twitterid 
WHERE s.live = 1 
GROUP BY s.twitterid;

SELECT COUNT(*) FROM stpsa_spam_scores_temp;
SELECT LOCALTIMESTAMP() AS 'Time';

DROP TABLE IF EXISTS stpsa_spam_scores_old;

RENAME TABLE stpsa_spam_scores TO stpsa_spam_scores_old;

SELECT COUNT(*) FROM stpsa_spam_scores_old;
SELECT LOCALTIMESTAMP() AS 'Time';

DROP TABLE IF EXISTS stpsa_spam_scores;

RENAME TABLE stpsa_spam_scores_temp TO stpsa_spam_scores;

SELECT COUNT(*) FROM stpsa_spam_scores;
SELECT LOCALTIMESTAMP() AS 'Time';

DROP TABLE statuspeople_spam.temp_user_info;

DROP TABLE statuspeople_spam.temp_spam_scores;
