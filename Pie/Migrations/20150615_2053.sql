alter table spdv_scores  drop twitterid;
alter table spdv_scores add column twitterid bigint(11) not null first;
alter table spdv_scores add column diveid int(11) not null first;
alter table spdv_scores add column id int(11) primary key auto_increment not null first;