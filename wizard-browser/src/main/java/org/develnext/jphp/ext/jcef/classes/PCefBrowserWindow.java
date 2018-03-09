package org.develnext.jphp.ext.jcef.classes;

import org.cef.browser.CefBrowser;
import org.develnext.jphp.ext.jcef.JCefExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Setter;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

import javax.swing.*;
import java.awt.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

@Reflection.Name("CefBrowserWindow")
@Reflection.Namespace(JCefExtension.NS)
public class PCefBrowserWindow extends BaseObject {
    private Window frame;
    private boolean alwaysOnTop = false;

    public PCefBrowserWindow(Environment env, Window wrappedObject) {
        super(env);
        frame = wrappedObject;
    }

    public PCefBrowserWindow(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(CefBrowser browser) {
        __construct(browser, false, false);
    }

    @Signature
    public void __construct(CefBrowser browser, boolean asDialog) {
        __construct(browser, asDialog, false);
    }

    @Signature
    public void __construct(CefBrowser browser, boolean asDialog, boolean undecorated) {
        if (asDialog) {
            JDialog jDialog = new JDialog();
            jDialog.setUndecorated(undecorated);
            jDialog.getContentPane().add(browser.getUIComponent());
            jDialog.setDefaultCloseOperation(WindowConstants.DO_NOTHING_ON_CLOSE);
            jDialog.setResizable(false);

            this.frame = jDialog;
        } else {
            JFrame jframe = new JFrame();
            jframe.setUndecorated(undecorated);
            jframe.getContentPane().add(browser.getUIComponent());
            jframe.setDefaultCloseOperation(WindowConstants.DO_NOTHING_ON_CLOSE);
            jframe.setResizable(false);

            frame = jframe;
        }
    }

    @Getter
    public boolean getEnabled() {
        return frame.isEnabled();
    }

    @Setter
    public void setEnabled(boolean value) {
        frame.setEnabled(value);
    }

    @Getter
    public boolean getActive() {
        return frame.isActive();
    }

    @Getter
    public int[] getInnerSize() {
        Container contentPane = null;

        if (frame instanceof JFrame) {
            contentPane = ((JFrame) frame).getContentPane();
        } else if (frame instanceof JDialog) {
            contentPane = ((JDialog) frame).getContentPane();
        }

        if (contentPane == null) {
            return new int[] { -1, -1 };
        }

        return new int[] { contentPane.getWidth(), contentPane.getHeight() };
    }

    @Setter
    public void setInnerSize(int[] size) {
        Container contentPane = null;

        if (frame instanceof JFrame) {
            contentPane = ((JFrame) frame).getContentPane();
        } else if (frame instanceof JDialog) {
            contentPane = ((JDialog) frame).getContentPane();
        }

        if (contentPane == null) {
            return;
        }

        contentPane.setPreferredSize(new Dimension(size[0], size[1]));
        contentPane.setSize(size[0], size[1]);
        frame.pack();
    }

    @Setter
    public void setSize(int[] size) {
        if (size.length >= 2) {
            frame.setSize(size[0], size[1]);
        }
    }

    @Getter
    public int[] getSize() {
        return new int[]{frame.getSize().width, frame.getSize().height};
    }

    @Setter
    public void setPosition(int[] pos) {
        if (pos.length >= 2) {
            frame.setLocation(pos[0], pos[1]);
        }
    }

    @Getter
    public float getOpacity() {
        return frame.getOpacity();
    }

    @Setter
    public void setOpacity(float opacity) {
        frame.setOpacity(opacity);
    }

    @Getter
    public int[] getPosition() {
        Point location = frame.getLocation();
        return new int[]{location.x, location.y};
    }

    @Getter
    public String getTitle() {
        if (frame instanceof JDialog) {
            return ((JDialog) frame).getTitle();
        } else if (frame instanceof JFrame) {
            return ((JFrame) frame).getTitle();
        }

        return null;
    }

    @Setter
    public void setTitle(String title) {
        if (frame instanceof JDialog) {
            ((JDialog) frame).setTitle(title);
        } else if (frame instanceof JFrame) {
            ((JFrame) frame).setTitle(title);
        }
    }

    @Getter
    public boolean getAlwaysOnTop() {
        return alwaysOnTop;
    }

    @Setter
    public void setAlwaysOnTop(boolean value) {
        frame.setAlwaysOnTop(value);
        this.alwaysOnTop = value;
    }

    @Getter
    public Window.Type getType() {
        return frame.getType();
    }

    @Setter
    public void setType(Window.Type type) {
        frame.setType(type);
    }

    @Setter
    public void setResizable(boolean value) {
        if (frame instanceof Frame) {
            ((Frame) frame).setResizable(value);
        } else if (frame instanceof Dialog) {
            ((Dialog) frame).setResizable(value);
        }
    }

    @Getter
    public boolean getResizable() {
        if (frame instanceof Frame) {
            return ((Frame) frame).isResizable();
        } else if (frame instanceof Dialog) {
            return ((Dialog) frame).isResizable();
        }

        return false;
    }

    @Setter
    public void setUndecorated(boolean value) {
        if (frame instanceof Frame) {
            ((Frame) frame).setUndecorated(value);
        } else if (frame instanceof Dialog) {
            ((Dialog) frame).setUndecorated(value);
        }
    }

    @Getter
    public boolean getUndecorated() {
        if (frame instanceof Frame) {
            return ((Frame) frame).isUndecorated();
        } else if (frame instanceof Dialog) {
            return ((Dialog) frame).isUndecorated();
        }

        return false;
    }

    @Setter
    public void setModal(boolean value) {
        if (frame instanceof Frame) {
            throw new IllegalStateException("Only dialogs can be modal");
        } else if (frame instanceof Dialog) {
            ((Dialog) frame).setModal(value);
        }
    }

    @Getter
    public boolean getModal() {
        if (frame instanceof Frame) {
            throw new IllegalStateException("Only dialogs can be modal");
        } else if (frame instanceof Dialog) {
            return ((Dialog) frame).isModal();
        }

        return false;
    }

    @Signature
    public void show() {
        frame.setVisible(true);
    }

    @Signature
    public void hide() {
        frame.setVisible(false);
    }

    @Signature
    public void free() {
        frame.dispose();
    }

    @Signature
    public void centerOnScreen() {
        frame.setLocationRelativeTo(null);
    }

    @Signature
    public void toFront() {
        frame.toFront();
    }

    @Signature
    public void toBack() {
        frame.toBack();
    }

    @Signature
    public void onClosed(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosed(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public void onActivated(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowActivated(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public void onDeactivated(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowDeactivated(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public void onClosing(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public void onOpened(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowOpened(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public void onIconified(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowIconified(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public void onDeiconified(Invoker invoker) {
        frame.addWindowListener(new WindowAdapter() {
            @Override
            public void windowDeiconified(WindowEvent e) {
                invoker.callAny(PCefBrowserWindow.this);
            }
        });
    }

    @Signature
    public void maximize() {
        if (frame instanceof JFrame) {
            ((JFrame) frame).setExtendedState(((JFrame) frame).getExtendedState() | JFrame.MAXIMIZED_BOTH);
        }
    }

    @Signature
    public static PCefBrowserWindow find(Environment env, CefBrowser browser) {
        Component uiComponent = browser.getUIComponent();
        Window window = SwingUtilities.getWindowAncestor(uiComponent);

        if (window != null) {
            return new PCefBrowserWindow(env, window);
        }

        return null;
    }
}
