/*
 * SQL Tables DDL for BCLN LOR (games, videos, projects)
 */

CREATE TABLE mdl_lor_type (
	id BIGINT AUTO_INCREMENT,
	name VARCHAR(100),
	PRIMARY KEY (id)
);

CREATE TABLE mdl_lor_platform (
	id BIGINT AUTO_INCREMENT,
	name VARCHAR(100) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE mdl_lor_content (
	id BIGINT AUTO_INCREMENT,
	type BIGINT NOT NULL,
	title VARCHAR(250) NOT NULL,
	image VARCHAR(250) NOT NULL,
	link VARCHAR(250) NOT NULL,
	date_created DATE NOT NULL,
	status VARCHAR(15) DEFAULT 'approved',
	author_email VARCHAR(250),
	platform BIGINT,
	PRIMARY KEY (id),
	FOREIGN KEY (platform) REFERENCES mdl_lor_platform (id),
	FOREIGN KEY (type) REFERENCES mdl_lor_type(id)
);

CREATE TABLE mdl_lor_keyword (
	name VARCHAR(100),
	PRIMARY KEY (name)
);

CREATE TABLE mdl_lor_content_keywords (
	content BIGINT,
	keyword VARCHAR(100),
	PRIMARY KEY (content, keyword)
);

CREATE TABLE mdl_lor_category (
	id BIGINT AUTO_INCREMENT,
	name VARCHAR(100),
	PRIMARY KEY (id)
);

CREATE TABLE mdl_lor_content_categories (
	content BIGINT,
	category BIGINT,
	PRIMARY KEY (content, category),
	FOREIGN KEY (content) REFERENCES mdl_lor_content(id),
	FOREIGN KEY (category) REFERENCES mdl_lor_category(id)
);

CREATE TABLE mdl_lor_grade (
	grade INT,
	PRIMARY KEY (grade)
);

CREATE TABLE mdl_lor_content_grades (
	content BIGINT,
	grade INT,
	PRIMARY KEY (content, grade),
	FOREIGN KEY (content) REFERENCES mdl_lor_content(id),
	FOREIGN KEY (grade) REFERENCES mdl_lor_grade(grade)
);

CREATE TABLE mdl_lor_contributor (
	id BIGINT AUTO_INCREMENT,
	name VARCHAR(250),
	PRIMARY KEY (id)
);

CREATE TABLE mdl_lor_content_contributors (
	content BIGINT,
	contributor BIGINT,
	PRIMARY KEY (content, contributor),
	FOREIGN KEY (content) REFERENCES mdl_lor_content(id),
	FOREIGN KEY (contributor) REFERENCES mdl_lor_contributor(id)
);
