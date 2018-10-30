package digital5.nantien;

import android.app.ProgressDialog;
import android.content.Intent;
import android.support.design.widget.NavigationView;
import android.support.design.widget.TabLayout;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.widget.Toolbar;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.view.ViewPager;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;

import android.widget.TextView;
import android.widget.ListView;

import android.os.AsyncTask;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLConnection;

import java.util.ArrayList;
import java.util.List;

import org.json.*;

import digital5.nantien.Activity.*;
import digital5.nantien.Models.EventItem;
import digital5.nantien.Models.EventListItem;
import digital5.nantien.Models.API_urls;


public class HomePage extends MainActivity {

    /**
     * The {@link android.support.v4.view.PagerAdapter} that will provide
     * fragments for each of the sections. We use a
     * {@link FragmentPagerAdapter} derivative, which will keep every
     * loaded fragment in memory. If this becomes too memory intensive, it
     * may be best to switch to a
     * {@link android.support.v4.app.FragmentStatePagerAdapter}.
     */
    private SectionsPagerAdapter mSectionsPagerAdapter;

    /**
     * The {@link ViewPager} that will host the section contents.
     */
    private ViewPager mViewPager;

    //holding event items
    private static ArrayList<EventListItem> dataItemList;

    static {
        dataItemList = new ArrayList<>();

        for(int i = 0; i < 15; i++){
            addItem(new EventListItem("Event:"+i, "Sample Name", "Sample Description", 0%2));
        }
    }

    public static void addItem(EventListItem item){
        dataItemList.add(item);
    }


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        getLayoutInflater().inflate(R.layout.activity_home_page, frameLayout);
        toolbar.setTitle("Home");

        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        // Create the adapter that will return a fragment for each of the three
        // primary sections of the activity.
        mSectionsPagerAdapter = new SectionsPagerAdapter(getSupportFragmentManager());

        // Set up the ViewPager with the sections adapter.
        mViewPager = (ViewPager) findViewById(R.id.container);
        mViewPager.setAdapter(mSectionsPagerAdapter);

        TabLayout tabLayout = (TabLayout) findViewById(R.id.tabs);
        tabLayout.setupWithViewPager(mViewPager);

        // mViewPager.addOnPageChangeListener();

        //tabLayout.getTabAt(0).setCustomView(mViewPager);


        drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                this, drawer, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close);
        drawer.addDrawerListener(toggle);
        toggle.syncState();

        navigationView = (NavigationView) findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(this);

        // Get the event list data
        GetEventList getEventList = new GetEventList();
        getEventList.execute();
    }

    private class GetEventList extends AsyncTask<URL, Integer, ArrayList<EventListItem>> {

        // event list api url
        final API_urls api_url = new API_urls();

        // progress dialog
        ProgressDialog dialog = new ProgressDialog(HomePage.this);

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
                    json = api_url.getPopularList(api_page_number);

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

            PlaceholderFragment.refreshData(result);

            mSectionsPagerAdapter.notifyDataSetChanged();
            mViewPager.setAdapter(mSectionsPagerAdapter);

            if (dialog.isShowing()) {
                dialog.dismiss();
            }

            Log.e("async", "finished post execute");
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        // to check current activity in the navigation drawer
        navigationView.getMenu().getItem(0).setChecked(true);
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_home_page, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    /**
     * A placeholder fragment containing a simple view.
     */
    public static class PlaceholderFragment extends Fragment {
        /**
         * The fragment argument representing the section number for this
         * fragment.
         */
        private static final String ARG_SECTION_NUMBER = "section_number";

        // ArrayList and ArrayAdapter
        static ArrayList<String> items = new ArrayList<>();

        static ArrayList<String> featured_items = new ArrayList<>();
        static ArrayList<String> popular_items = new ArrayList<>();

        static ArrayAdapter<String> arrayAdapter;

        static ArrayList<EventListItem> item_data = new ArrayList<>();


        public PlaceholderFragment() {

        }

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

        /**
         * Returns a new instance of this fragment for the given section
         * number.
         */
        public static PlaceholderFragment newInstance(int sectionNumber) {
            PlaceholderFragment fragment = new PlaceholderFragment();
            Bundle args = new Bundle();
            args.putInt(ARG_SECTION_NUMBER, sectionNumber);
            fragment.setArguments(args);
            return fragment;
        }

        @Override
        public View onCreateView(LayoutInflater inflater, ViewGroup container,
                                 Bundle savedInstanceState) {
            View rootView = inflater.inflate(R.layout.home_events, container, false);

            TextView featured_text = (TextView) rootView.findViewById(R.id.featured_desc);
            featured_text.setText("Nan Tien Temple known as “Southern Paradise” is the largest Buddhist temple in the Southern Hemisphere.\n" );

            //building adapter , connecting with event_items.xml
            arrayAdapter = new ArrayAdapter<>(getActivity(), R.layout.event_items, items);

            ListView eventsList = (ListView) rootView.findViewById(R.id.events_list);
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



    /**
     * A {@link FragmentPagerAdapter} that returns a fragment corresponding to
     * one of the sections/tabs/pages.
     */
    public class SectionsPagerAdapter extends FragmentPagerAdapter {

        public SectionsPagerAdapter(FragmentManager fm) {
            super(fm);
        }

        @Override
        public Fragment getItem(int position) {
            // getItem is called to instantiate the fragment for the given page.
            // Return a PlaceholderFragment (defined as a static inner class below).
            return PlaceholderFragment.newInstance(position + 1);
        }

        @Override
        public int getCount() {
            // Show 2 total pages.
            return 2;
        }

        @Override
        public CharSequence getPageTitle(int position) {
            switch (position) {
                case 0:
                    return "FEATURED";
                case 1:
                    return "POPULAR";
            }
            return null;
        }
    }


}
