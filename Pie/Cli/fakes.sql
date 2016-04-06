USE statuspeople_spam;

DELETE FROM spsp_fakes WHERE created <= UNIX_TIMESTAMP() - (3600*24*95);