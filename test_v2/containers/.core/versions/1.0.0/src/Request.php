<?php
/**
 * MIT License
 *
 * Copyright (c) 2024 Masato Nakatsuji
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace kiwi\core;

class Request {

    /**
     * Request Method (Get\Post\Put\Delete\Option\Other)
     */
    public static RequestMethod $method = RequestMethod::Other;

    /**
     * リクエストデータ取得
     */
    public static function get(string $name = null) : array {
        return [];
    }
}

class RequestMethod {
    const Get = "GET";
    const Post = "POST";
    const Put = "PUT";
    const Delete = "DELETE";
    const Options = "OPTIONS";
    const Other = null;
}

class Get extends Request {

    /**
     * Request Method (= GET)
     */
    public static RequestMethod $method = RequestMethod::Get;
}

class Post extends Request {

    /**
     * Request Method (= POST)
     */
    public static RequestMethod $method = RequestMethod::Post;
}

class Put extends Request {

    /**
     * Request Method (= PUT)
     */
    public static RequestMethod $method = RequestMethod::Put;
}

class Options extends Request {

    /**
     * Request Method (= OPTIONS)
     */
    public static RequestMethod $method = RequestMethod::Options;
}

class Delete extends Request {

    /**
     * Request Method (= DELETE)
     */
    public static RequestMethod $method = RequestMethod::Delete;
}