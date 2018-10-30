package digital5.nantien.Models;

import android.util.Log;

import org.json.JSONObject;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLConnection;

/**
 * Stores info on all the api urls the app uses
 */

public class API_urls {

    final private String host = "https://ntt.07.gs/";
    final private String featured = "api/event/featured/";
    final private String popular = "api/event/popular/";

    public API_urls() {

    }

    public String featuredList(int pageNum) {
        return (host + featured + pageNum);
    }

    public String popularList(int pageNum) {
        return (host + popular + pageNum);
    }

    // Used in readJsonFromUrl to get all the data from an InputStream
    private String readStream(InputStream is) throws IOException {
        StringBuilder sb = new StringBuilder();
        BufferedReader r = new BufferedReader(new InputStreamReader(is));
        for (String line = r.readLine(); line != null; line =r.readLine()){
            sb.append(line);
        }
        is.close();
        return sb.toString();
    }

    // Open url connection and get the json data from url
    private JSONObject readJsonFromUrl(String url_string) throws IOException {
        URL url = new URL(url_string);
        URLConnection urlConnection = url.openConnection();
        urlConnection.setRequestProperty("User-Agent", "test"); // User Agent

        try {
            InputStream in = new BufferedInputStream(urlConnection.getInputStream());
            String jsonText = readStream(in);
            return new JSONObject(jsonText);
        } catch (Exception e) {
            e.printStackTrace();
        }

        return new JSONObject();
    }

    // Returns json of featured api for page number
    public JSONObject getFeaturedList(int id) {
        try {
            Log.e("api_url", featuredList(id));
            return readJsonFromUrl(featuredList(id));
        } catch (Exception e) {
            e.printStackTrace();
        }

        return new JSONObject();
    }

    // Returns json of popular api for page number
    public JSONObject getPopularList(int id) {
        try {
            return readJsonFromUrl(popularList(id));
        } catch (Exception e) {
            e.printStackTrace();
        }

        return new JSONObject();
    }

    // Returns json of popular api for page number
    public JSONObject getEventDetails(int id) {
        try {
            return readJsonFromUrl(eventDetail(id));
        } catch (Exception e) {
            e.printStackTrace();
        }

        return new JSONObject();
    }

    public String eventList(int pageNum) {
        String event_get = ("api/event/get/" + pageNum);
        return (host + event_get);
    }

    public String eventDetail(int id) {
        String event_detail = ("api/event/detail/" + id);
        return (host + event_detail);
    }

}
