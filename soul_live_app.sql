CREATE TABLE IF NOT EXISTS member(
    band_id INT auto_increment PRIMARY KEY,
    live_id VARCHAR(10),
    member VARCHAR(50),
    performance_time varchar(20),
    performance_num int DEFAULT 1
);

DESC member;

CREATE TABLE IF NOT EXISTS band(
    band_id INT auto_increment PRIMARY KEY,
    band_name VARCHAR(20)
);

DESC band;

CREATE TABLE IF NOT EXISTS live(
    live_id VARCHAR(10) PRIMARY KEY,
    live_name VARCHAR(30)
);

DESC live;

CREATE TABLE IF NOT EXISTS schedule(
    live_id VARCHAR(10) PRIMARY KEY,
    live_schedule VARCHAR(10)
);

DESC schedule;