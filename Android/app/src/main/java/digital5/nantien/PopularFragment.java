package digital5.nantien;


import android.app.ProgressDialog;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONObject;

import java.net.URL;
import java.util.ArrayList;

import digital5.nantien.Home;
import digital5.nantien.Models.API_urls;
import digital5.nantien.Models.EventListItem;

public class PopularFragment extends Fragment {

    public PopularFragment(){

    }

    @Override
    public void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);

        // Get the event list data
        GetEventList getEventList = new GetEventList();
        getEventList.execute();
    }


    private class GetEventList extends AsyncTask<URL, Integer, ArrayList<EventListItem>> {

        // event list api url
        final API_urls api_url = new API_urls();

        // progress dialogjson = api_url.getPopularList(api_page_number);
        ProgressDialog dialog = new ProgressDialog(getActivity());

        // array list of event list items
        ArrayList<EventListItem> event_list = new ArrayList<>();

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            //ProgressDialog dialog = new ProgressDialog(HomePage.this);
            dialog.setMessage("Loading...");
            dialog.setIndeterminate(true);
            dialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
            dialog.setCancelable(true);
            dialog.show();
        }

        @Override
        protected ArrayList<EventListItem> doInBackground(URL... urls) {

            Log.e("async", "start");

            EventListItem event_item;

            // Getting JSON from URL
            try {
                int api_page_number = 1;
                JSONObject json;

                do {
                    // Get the event page of url_number
                    // json = readJsonFromUrl(api_url.eventList(api_page_number));
                    json = api_url.getFeaturedList(api_page_number);

                    // If there is a server error retrieving data
                    if(json.getString("status").equals("error")) {
                        //Log.e("async", "error retrieving server data: " +json.get("message"));
                        continue;
                    } else if (json.getString("status").equals("fail")) {
                        //Log.e("async", "fail retrieving server data: " +json.getString("message"));
                        continue;
                    }

                    // Make a json array from json data
                    JSONArray jsonArray = json.getJSONObject("data").getJSONArray("events");

                    for(int i = 0; i < json.length()-1; i++)
                    {
                        event_item = new EventListItem();

                        event_item.setItemID(jsonArray.getJSONObject(i).getString("id"));
                        event_item.setItemName(jsonArray.getJSONObject(i).getString("title"));
                        event_item.setDescription(jsonArray.getJSONObject(i).getString("summary"));
                        event_item.setSticky(jsonArray.getJSONObject(i).getInt("sticky"));

                        event_list.add(event_item);
                    }

                    api_page_number++;

                    // Only get a certain amount of events
                    if(event_list.size() >= 20) {
                        break;
                    }

                } while(json.getJSONObject("data").get("hasNext").equals(true));


            }
            catch (Exception e) {
                Log.e("async", "processing json data");
                e.printStackTrace();
            }

            Log.e("async", "done thread");

            return event_list;
        }

        protected void onPostExecute(ArrayList<EventListItem> result) {

            refreshData(result);

            if (dialog.isShowing()) {
                dialog.dismiss();
            }

            Log.e("async", "finished post execute");
        }
    }


    // ArrayList and ArrayAdapter
    static ArrayList<String> items = new ArrayList<>();

    static ArrayList<String> featured_items = new ArrayList<>();
    static ArrayList<String> popular_items = new ArrayList<>();

    static ArrayAdapter<String> arrayAdapter;

    static ArrayList<EventListItem> item_data = new ArrayList<>();




    // Refreshes the data in items list
    // Used in EventListGet
    public static void refreshData(ArrayList<EventListItem> data) {

        // Update arraylist of eventlistitem
        if(!data.isEmpty()) {

            item_data.clear();
            for(EventListItem item : data) {
                item_data.add(item);
            }

        }


        // Update arraylist of string names with item_data
        if(!item_data.isEmpty()) {

            items.clear();

            popular_items.clear();
            featured_items.clear();

            for(EventListItem item : item_data) {

                if(item.getSticky() == 1)
                    featured_items.add(item.getItemName());
                else
                    popular_items.add(item.getItemName());

                items.add(item.getItemName());

            }

        }

        arrayAdapter.notifyDataSetChanged();
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.popular_fragment, container, false);
        // Inflate the layout for this fragment

        TextView featured_text = (TextView) rootView.findViewById(R.id.popular_desc);
        featured_text.setText("This is the Popular Description:" +
                "Nan Tien Temple known as “Southern Paradise” is the largest Buddhist temple in the Southern Hemisphere.\n" );


        //building adapter , connecting with event_items.xml
        arrayAdapter = new ArrayAdapter<>(getActivity(), R.layout.popular_items, items);

        ListView eventsList = (ListView) rootView.findViewById(R.id.popular_events);
        eventsList.setAdapter(arrayAdapter);


        // OnItemClick, goto event details
        eventsList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Intent intent = new Intent(view.getContext(), event_details.class);

                //Log.e("listview", position +", " +item_data.get(position).getItemID());

                intent.putExtra("EventID", item_data.get(position).getItemID());
                startActivity(intent);
            }
        });

        return rootView;
    }




}
