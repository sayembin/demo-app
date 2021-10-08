DROP TABLE IF EXISTS billbox_user CASCADE;
CREATE TABLE billbox_user(
            id SERIAL PRIMARY KEY,
            user_name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL,
            password text NOT NULL
         );


DROP TABLE IF EXISTS billbox_order CASCADE;
CREATE TABLE billbox_order (
            id SERIAL PRIMARY KEY,
            user_id int NOT NULL,
            create_date bigint ,
            FOREIGN KEY (user_id) REFERENCES billbox_user(id) ON DELETE CASCADE
        );

DROP TABLE IF EXISTS billbox_items;
CREATE TABLE billbox_items (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            quantity int NOT NULL,
            item_price float NOT NULL,
            total_price float NOT NULL,
            order_id int NOT NULL,
            FOREIGN KEY (order_id) REFERENCES billbox_order(id) ON DELETE CASCADE
        );