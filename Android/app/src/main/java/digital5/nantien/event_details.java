package digital5.nantien;

import android.app.ProgressDialog;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.NavigationView;
import android.support.design.widget.Snackbar;
import android.support.design.widget.TabLayout;
import android.support.v4.view.ViewPager;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.text.Html;
import android.text.Spanned;
import android.util.Log;
import android.view.View;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONObject;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLConnection;
import java.util.ArrayList;

import digital5.nantien.Activity.*;
import digital5.nantien.Models.API_urls;
import digital5.nantien.Models.EventDetailItem;
import digital5.nantien.Models.EventItem;
import digital5.nantien.Models.EventListItem;

import static digital5.nantien.R.id.container;

public class event_details extends MainActivity {

    static EventDetailItem event_details_item = new EventDetailItem();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getLayoutInflater().inflate(R.layout.activity_event_details, frameLayout);

        toolbar.setTitle("Event Details");

        // Set the text view
        TextView bodyText = (TextView) findViewById(R.id.event_details_text);
        bodyText.setText(event_details_item.getDescription());

        // Get the event list data
        new GetEventDetails(bodyText).execute();

        //Log.e("event", event_id);
    }

    private class GetEventDetails extends AsyncTask<URL, Integer, EventDetailItem> {

        // event list api url
        final API_urls api_url = new API_urls();

        // progress dialog
        ProgressDialog dialog = new ProgressDialog(event_details.this);

        TextView textView;

        private GetEventDetails(TextView textView) {
            this.textView = textView;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            ProgressDialog dialog = new ProgressDialog(event_details.this);
            dialog.setMessage("Loading...");
            dialog.setIndeterminate(false);
            dialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
            dialog.setCancelable(true);
            dialog.show();
        }


        @Override
        protected EventDetailItem doInBackground(URL... urls) {

            Log.e("async", "start");

            EventDetailItem event_item = new EventDetailItem();

            // Getting JSON from URL
//            try {
//                int api_page_number = 1;
//                JSONObject json;
//
//                String event_id = getIntent().getStringExtra("EventID");
//
//                // Get the event page of url_number
//                json = api_url.getEventDetails(Integer.valueOf(event_id));
//
//                // If there is a server error retrieving data
//                if(json.getString("status").equals("error")) {
//                    //break;
//                } else if (json.getString("status").equals("fail")) {
//                    //continue;
//                }
//
//                // Make a json array from json data
//                JSONObject jsonObject = json.getJSONObject("data").getJSONObject("event");
//
//                event_item = new EventDetailItem();
//
//                event_item.setItemName(jsonObject.getString("title"));
//                event_item.setDescription(jsonObject.getString("body"));
//
//                //event_item.setSticky(jsonObject.getInt("sticky"));
//
//            }
//            catch (Exception e) {
//                e.printStackTrace();
//            }

            Log.e("async", "done thread");

            return event_item;
        }


        @Override
        protected void onPostExecute(EventDetailItem result) {

            event_details_item.setDescription(result.getDescription());

            runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.N) {
                        textView.setText(Html.fromHtml(event_details_item.getDescription(), Html.FROM_HTML_MODE_LEGACY));
                    } else {
                        textView.setText(Html.fromHtml(event_details_item.getDescription()));
                    }
                }
            });

            if (dialog.isShowing()) {
                dialog.dismiss();
            }

        }
    }
    
    @Override
    protected void onResume() {
        super.onResume();
        // to check current activity in the navigation drawer
//         navigationView.getMenu().getItem(2).setChecked(true);
       }

}
