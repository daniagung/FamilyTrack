package biz.prolike.hereiam.activities;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.CollapsingToolbarLayout;
import android.support.design.widget.TabLayout;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;

import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;

import java.util.ArrayList;

import biz.prolike.hereiam.AppController;
import biz.prolike.hereiam.DesignDemoRecyclerAdapter;
import biz.prolike.hereiam.R;
import biz.prolike.hereiam.fragments.TabCalls;
import biz.prolike.hereiam.fragments.TabLocations;
import biz.prolike.hereiam.fragments.TabSMS;
import biz.prolike.hereiam.utils.DatabaseHandler;

/**
 * Created by admin on 9/29/15.
 */
public class ChildDetails  extends AppCompatActivity {

    DatabaseHandler db;
    SharedPreferences prefs = null;
    String id;

    ImageLoader imageLoader = AppController.getInstance().getImageLoader();
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.child_details);

        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);



        prefs = getSharedPreferences("biz.prolike.hereiam", MODE_PRIVATE);
        id = prefs.getString("seek", "");
        db = new DatabaseHandler(this);

        String childName = db.getChild(id,"name");
        if (imageLoader == null)
            imageLoader = AppController.getInstance().getImageLoader();
        NetworkImageView thumbNail = (NetworkImageView) findViewById(R.id.backdrop);
        String imgurl = db.getChild(id, "photo").substring(0, db.getChild(id, "photo").length() - 2);
        imgurl = imgurl+"700";
        thumbNail.setImageUrl(imgurl, imageLoader);
        Log.d("------","Total:"+ db.getChildCount()+" ChildID: " + id +" ChildName: " + childName);

        CollapsingToolbarLayout collapsingToolbar = (CollapsingToolbarLayout) findViewById(R.id.collapsing_toolbar);
        collapsingToolbar.setTitle(childName);


        DesignDemoPagerAdapter adapter = new DesignDemoPagerAdapter(getSupportFragmentManager());
        ViewPager viewPager = (ViewPager)findViewById(R.id.viewpager);
        viewPager.setAdapter(adapter);
        TabLayout tabLayout = (TabLayout)findViewById(R.id.tablayout);
        tabLayout.setupWithViewPager(viewPager);
        //tabLayout.getTabAt(0).setIcon(R.drawable.white_info);
        tabLayout.getTabAt(0).setIcon(R.drawable.white_phone);
        tabLayout.getTabAt(1).setIcon(R.drawable.white_sms);
        tabLayout.getTabAt(2).setIcon(R.drawable.white_pin);


    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        switch (id) {
            case android.R.id.home:
                finish();
                return true;
            case R.id.action_settings:
                return true;
        }

        return super.onOptionsItemSelected(item);
    }

    public static class DesignDemoFragment extends Fragment {
        private static final String TAB_POSITION = "tab_position";

        public DesignDemoFragment() {

        }

        public static DesignDemoFragment newInstance(int tabPosition) {
            DesignDemoFragment fragment = new DesignDemoFragment();
            Bundle args = new Bundle();
            args.putInt(TAB_POSITION, tabPosition);
            fragment.setArguments(args);
            return fragment;
        }

        @Nullable
        @Override
        public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
            Bundle args = getArguments();
            int tabPosition = args.getInt(TAB_POSITION);

            ArrayList<String> items = new ArrayList<String>();
            for (int i = 0; i < 50; i++) {
                items.add("Tab #" + tabPosition + " item #" + i);
            }

            View v =  inflater.inflate(R.layout.fragment_list_view, container, false);
            RecyclerView recyclerView = (RecyclerView)v.findViewById(R.id.recyclerview);
            recyclerView.setLayoutManager(new LinearLayoutManager(getActivity()));
            recyclerView.setAdapter(new DesignDemoRecyclerAdapter(items));

            return v;
        }
    }

    static class DesignDemoPagerAdapter extends FragmentStatePagerAdapter {

        public DesignDemoPagerAdapter(FragmentManager fm) {
            super(fm);
        }

        @Override
        public Fragment getItem(int position) {
           // return DesignDemoFragment.newInstance(position);
            if(position == 0)
            {

                TabCalls tabCalls = new TabCalls();
                return tabCalls;


            } else if(position == 1)
            {

                TabSMS tabSMS = new TabSMS();
                return tabSMS;

            } else if(position == 2)
            {

                TabLocations tabLocations = new TabLocations();
                return tabLocations;

            }
            else {
                TabCalls tabCalls = new TabCalls();
                return tabCalls;

            }
        }

        @Override
        public int getCount() {
            return 3;
        }

        @Override
        public CharSequence getPageTitle(int position) {
            return "";
        }
    }

}
